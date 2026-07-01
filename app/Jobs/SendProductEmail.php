<?php

namespace App\Jobs;

use App\Mail\ProductCreated;
use App\Mail\ProductUpdated;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class SendProductEmail implements ShouldQueue
{
    use Dispatchable, Queueable;
    use SerializesModels;

    public Product $product;
    public string $action;

    public function __construct(Product $product, string $action)
    {
        $this->product = $product;
        $this->action = $action;
    }

    public function handle(): void
    {
        $email = config('mail.from.address');
        $mailable = $this->action === 'created'
            ? new ProductCreated($this->product)
            : new ProductUpdated($this->product);

        Mail::to($email)->send($mailable);
    }
}
