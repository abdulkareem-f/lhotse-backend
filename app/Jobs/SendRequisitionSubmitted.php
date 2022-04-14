<?php

namespace App\Jobs;

use App\Mail\RequisitionSubmitted;
use App\Models\Requisition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequisitionSubmitted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Requisition $requisition;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requisitionFolderPath = public_path("uploads\\pdf\\requisition");
        $requisitionFilePath = "$requisitionFolderPath\\{$this->requisition->id}.pdf";
        if(!file_exists($requisitionFilePath)) {
            if(!file_exists($requisitionFolderPath))
                mkdir($requisitionFolderPath);

            try {
                $pdf = PDF::loadView('pdf/requisition_items_pdf', ['requisition' => $this->requisition]);
                $pdf->save($requisitionFilePath);
            } catch (\Exception $e){
                //
            }
        }
        $data = [
            'requisition'   =>  $this->requisition,
            'attach_file'   =>  $requisitionFilePath
        ];

        try {
            Mail::to('some@email.com')->send(new RequisitionSubmitted($data));
        } catch (\Exception $e){
            //
        }

    }
}
