<?php

namespace App\Http\Controllers;

use App\Invoice_items;
use App\Meals;
use App\Orders;
use Illuminate\Http\Request;
use App\Invoices;
use App\Http\Resources\InvoicesResource as InvoicesResource;
use App\Http\Resources\InvoiceItems as InvoiceItemsResource;
use Illuminate\Support\Facades\Response;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoices::paginate(10);
        return InvoicesResource::collection($invoices);
    }

    public function getPendingInvoces()
    {
        $invoices = Invoices::where('state', 'pending')->get();
        return InvoicesResource::collection($invoices);
    }

    public function getInvoicesByState($state)
    {
        if ($state === 'pending'){
            $invoices = Invoices::where('state', 'pending')->paginate(30);
            return InvoicesResource::collection($invoices);
        }elseif ($state === 'paid'){
            $invoices = Invoices::where('state', 'paid')->paginate(30);
            return InvoicesResource::collection($invoices);
        }elseif ($state === 'not paid'){
            $invoices = Invoices::where('state', 'not paid')->paginate(30);
            return InvoicesResource::collection($invoices);
        }else{
            $invoices = Invoices::where('state', 'pending')->paginate(30);
            return InvoicesResource::collection($invoices);
        }
    }

    public function getInvoicesAByDate($date)
    {
        $invoices = Invoices::whereDate('created_at', $date)->paginate(10);
        return InvoicesResource::collection($invoices);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'meal_id' => 'required',
            'total_price' => 'required',
            'state' => 'required'
        ]);

        $invoice = new Invoices();
        $invoice->meal_id = $data['meal_id'];
        $invoice->total_price = $data['total_price'];
        $invoice->date = now();
        $invoice->save();

        $orders = Orders::where('meal_id', $data['meal_id'])->where('state', '=', 'delivered')->get();
        //1-6-1
        $itemsIds = [];
        foreach($orders as $o) {
            if(!in_array($o->item_id, $itemsIds)) {
                $i = new Invoice_items();
                $count = 0;
                foreach ($orders as $o1) {
                    if ($o->item_id == $o1->item_id) {
                        $count++;
                    }
                }
                $i->invoice_id = $invoice->id;
                $i->item_id = $o->item_id;
                $i->quantity = $count;
                $i->unit_price = $o->item->price;
                $i->sub_total_price = $count * $o->item->price;
                $i->save();
                $itemsIds[] = $i->item_id;
            }
        }
        $items = Invoice_items::where('invoice_id', '=', $invoice->id)->get();
        return InvoiceItemsResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function allInvoices()
    {
        $allInvoices = Invoices::with(['items', 'meals'])->paginate(10);
        return InvoicesResource::collection($allInvoices);
    }

    public static function pendingInvoices()
    {

        $invoices = Invoices::where('state', '=', 'pending')->paginate(10);

        return InvoicesResource::collection($invoices);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function changeStateToNotPaid($id, $meal_id){
        $invoice = Invoices::findOrFail($id);
        $meal = Meals::findOrFail($meal_id);

        if(($invoice->state == "paid"  || $invoice->state == "not paid"))
        {
            return Response::json( ['error' => 'Invalid state to update'], 422);
        }

        $orders_changes = Orders::where([['meal_id',$meal_id],['state', '<>', 'delivered'], ['state', '<>', 'not delivered']])->get();
        foreach ($orders_changes as $order) {
            $order->state = "not delivered";
            $order->update();
        }
        $meal->state = "not paid";
        $invoice->state = "not paid";
        $invoice->update();
        $meal->update();
        return new InvoicesResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nif' => 'required',
            'name' => 'required',
        ]);
        $invoice = Invoices::where('id', $id)->first();
        $meal = Meals::where('id',$invoice->meal_id)->first();
        $meal->state = 'paid';
        $meal->update();
        $invoice->nif = $data['nif'];
        $invoice->name = $data['name'];
        $invoice->state = 'paid';
        $invoice->update();
        return new InvoicesResource($invoice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



}
