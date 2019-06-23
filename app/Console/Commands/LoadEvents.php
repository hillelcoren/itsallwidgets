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
    protected $signature = 'itsallwidgets:load_events';

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

        $data = file_get_contents('https://api.meetup.com/find/upcoming_events?page=100&text=flutter&radius=global&key=' . config('services.meetup.key'));
        $data = json_decode($data);
        $groups = [];

        foreach ($data->events as $item) {

            $group = $item->group;
            $city = '';

            if (property_exists($item, 'venue')) {
                $venue = $item->venue;
                $address = '';
                if (property_exists($venue, 'address_1')) {
                    $address = $venue->address_1 . ', ';
                }
                $city = $venue->city;
                $address .= $venue->city . ' ' . $venue->localized_country_name;
                $latitude = $venue->lat;
                $longitude = $venue->lon;
            } else {
                $address = $group->localized_location;
                $latitude = $group->lat;
                $longitude = $group->lon;
            }

            $data = [
                'event_name' => $item->name,
                'event_date' => $item->local_date,
                'description' => $item->description,
                'address' => $address,
                'city' => $city,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];

            $event = FlutterEvent::where('event_url', '=', $item->link)->first();

            if ($event) {
                $this->eventRepo->update($event, $data);
            } else {
                $data['banner'] = 'Join us at $event';
                $data['slug'] = str_slug($item->name);
                $data['event_url'] = $item->link;

                $event = $this->eventRepo->store($data, 1);

                $event->is_approved = 1;
                $event->save();
            }

            if (!isset($groups[$group->id])) {
                $groups[$group->id] = true;
                $this->info($group->name . ' - ' . $group->localized_location);
            }


        }

        $this->info('Done');
    }
}
