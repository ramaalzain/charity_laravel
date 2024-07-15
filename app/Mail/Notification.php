<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;
    public $data, $view;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$view)
    {
        $this->data = $data;
        $this->view = $view;
    }
    

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    return $this->from('ghufran@example.com', 'Example')
                ->view($this->view)->with(['data' => $this->data]);
}
}