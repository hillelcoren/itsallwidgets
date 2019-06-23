<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlutterEvent;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
                $event = FlutterEvent::where('event_url', '=', $item->link)->first();
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
                $this->info($group->name . ' ' . $group->who . ' ' . $group->localized_location);

        }



        $this->info('Done');
    }
}
