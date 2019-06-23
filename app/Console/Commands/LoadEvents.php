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

        foreach ($data->events as $item) {
                $venue = $item->venue;
                $address = $venue->address_1 . ', ' . $venue->city . ' ' . $venue->localized_country_name;
                $group = $item->group;

                $data = [
                    'event_name' => $item->name,
                    'event_date' => $item->local_date,
                    'slug' => str_slug($item->name),
                    'description' => $item->description,
                    'address' => $address,
                    'latitude' => $venue->lat,
                    'longitude' => $venue->lon,
                    'link' => $item->link,

                ];

                $event = FlutterEvent::where('event_url', '=', $item->link)->first();

                if ($event) {
                    $this->eventRepo->update($event, $data);
                } else {
                    $this->eventRepo->update($data, 1);
                }

                $this->info($group->name . ' ' . $group->who . ' ' . $group->localized_location);

        }

        $this->info('Done');
    }
}
