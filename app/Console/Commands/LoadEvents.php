<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterEvent;
use App\Repositories\FlutterEventRepository;

class LoadEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'itsallwidgets:load_events {--all} {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load events from meetup.com';

    protected $eventRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FlutterEventRepository $eventRepo)
    {
        parent::__construct();

        $this->eventRepo = $eventRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Running...');

        if ($this->option('all')) {
            $groups = FlutterEvent::groupBy('meetup_group_url')
                ->orderBy('meetup_group_url')
                ->get(['meetup_group_url']);

            foreach ($groups as $group) {
                if (! $group->meetup_group_url) {
                    continue;
                }

                $this->info('Loading ' . $group->meetup_group_url . '...');

                $data = file_get_contents('https://api.meetup.com/' . $group->meetup_group_url . '/events?page=1000&text=flutter&status=past&key=' . config('services.meetup.key'));
                $data = json_decode($data);

                $this->parseEvents($data);
            }

        } else {
            $this->info('Loading upcoming events...');

            if ($file = $this->option('file')) {
                $data = file_get_contents($file);
            } else {
                $data = file_get_contents('https://api.meetup.com/find/upcoming_events?page=1000&text=flutter&radius=global&key=' . config('services.meetup.key'));
            }

            $data = json_decode($data);
            $this->parseEvents($data->events);
        }

        $this->info('Done');
    }

    private function parseEvents($data)
    {
        $groups = [];

        foreach ($data as $item) {
            if (! property_exists($item, 'description')) {
                continue;
            }

            $searchStr = strtolower($item->name . $item->description . $item->group->name);

            if (strpos($searchStr, 'flutter') === false) {
                continue;
            }

            $group = $item->group;
            $city = '';
            $country = '';

            if (property_exists($item, 'venue')) {
                $venue = $item->venue;
                $address = '';
                if (property_exists($venue, 'address_1')) {
                    $address = $venue->address_1 . ', ';
                }
                $city = $venue->city;
                $country = $venue->localized_country_name;
                $address .= $venue->city . ' ' . $venue->localized_country_name;
                $latitude = $venue->lat;
                $longitude = $venue->lon;
            } else {
                $address = $group->localized_location;
                $latitude = $group->lat;
                $longitude = $group->lon;
            }

            $data = [
                'slug' => str_slug($group->name . '-' . $item->name . '-' . $item->id),
                'event_name' => $item->name,
                'event_date' => $item->local_date,
                'description' => $item->description,
                'rsvp_limit' => property_exists($item, 'rsvp_limit') ? $item->rsvp_limit : 0,
                'rsvp_yes' => $item->yes_rsvp_count,
                'rsvp_waitlist' => $item->waitlist_count,
                'meetup_id' => $item->id,
                'meetup_group_id' => $group->id,
                'meetup_group_name' => $group->name,
                'meetup_group_url' => $group->urlname,
                'directions' => property_exists($item, 'how_to_find_us') ? $item->how_to_find_us : '',
                'address' => $address,
                'city' => $city,
                'country' => $country,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];

            $event = FlutterEvent::where('meetup_id', '=', $item->id)
                ->orWhere('url', '=', $item->link)
                ->first();

            if ($event) {
                $this->eventRepo->update($event, $data);
            } else {
                $data['banner'] = 'Join us at $event in $city';
                $data['event_url'] = $item->link;

                $event = $this->eventRepo->store($data, 1);

                $event->is_approved = 1;
                $event->save();
            }

            $imageUrl = false;

            if ($contents = @file_get_contents($event->event_url)) {
                $matches = [];
                preg_match('/featured_photo&(.*?)(https.*?)&/', $contents, $matches);

                if (count($matches) == 3) {
                    $imageUrl = $matches[2];
                }
            }

            if (! $imageUrl) {
                // https://stackoverflow.com/a/9244634/497368
                libxml_use_internal_errors(true);

                if ($c = @file_get_contents('https://www.meetup.com/' . $group->urlname)) {
                    $doc = new \DomDocument();
                    $doc->loadHTML($c);
                    $xp = new \domxpath($doc);

                    foreach ($xp->query("//meta[@property='og:image']") as $el) {
                        $imageUrl = $el->getAttribute("content");
                        break;
                    }
                }
            }

            if ($imageUrl) {
                $parts = explode('?', $imageUrl);
                $imageUrl = count($parts) ? $parts[0] : '';
                $imageUrl = rtrim($imageUrl, '/');
                $parts = explode('.', $imageUrl);
                $extension = count($parts) > 1 ? '.' . $parts[count($parts) - 1] : '';
                if (strlen($extension) > 5) {
                    $extension = '';
                }

                if ($contents = @file_get_contents($imageUrl)) {
                    $url = '/events/event-' . $event->id . $extension;
                    $file = public_path($url);
                    @file_put_contents($file, $contents);
                    $event->image_url = $url;
                }

                $event->save();
            }

            if (!isset($groups[$group->id])) {
                $groups[$group->id] = true;
                $this->info($group->name . ' - ' . $group->localized_location);
            }
        }
    }
}
