<?php

namespace App\Http\Controllers\print;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentPaymentPrintController extends Controller
{
    public function index()
    {
        // Pass any data needed for the preview
        return Inertia::render('Admin/print/StudentPaymentInvoice', [
            'data' => [
                'title' => 'Sample Print Preview',
                'content' => 'This is the content to be printed.',
            ],
        ]);
    }

}
