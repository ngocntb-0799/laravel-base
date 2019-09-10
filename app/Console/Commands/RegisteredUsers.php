<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to registered users successfully';

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
        $users = User::all();

        foreach ($users as $user ) {
            Mail::to($user->email)->send(new WelcomeEmail());
        }

        $this->info('Sent email successfully!');
    }
}
