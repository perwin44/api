<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

     public $data;
    public function __construct($data)
    {
        $this->data=$data;
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->data as $_data){
             Comment::to($_data->comment)->send(new \App\Mail\TestMail());
         }
    }
}
