<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\StudentRegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StudentRegisterMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
       
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->data['mail_to'])->send(new StudentRegisterMail([
            'subject'=>$this->data['subject'],
            'message'=>$this->data['message'],
            'password'=>$this->data['password'],
            'user_name'=>$this->data['mail_to'],
            'name'=>$this->data['name'],
        ]));
    }
}
