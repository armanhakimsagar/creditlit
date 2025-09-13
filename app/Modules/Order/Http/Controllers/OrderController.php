<?php

namespace App\Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirm;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use App\Modules\Order\Models\Order;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    // To show all Order
    public function allOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.order_status')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    if ($row->order_status == 'pending') {
                        $btn = '<a class="btn btn-outline-info btn-xs" href="' . route('order.edit', [$row->id]) . '" target="_blank"><i class="fas fa-edit"></i></a> <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a> <a class="btn btn-outline-danger btn-xs" href="' . route('order.delete', [$row->id]) . '" id="delete"><i class="fas fa-trash"></i></a>';
                    } else {
                        $btn = '<a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.all_order', compact('bankId', 'country', 'supplierId'));
    }


    // To show pending Order
    public function pendingOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'pending')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.pending_order', compact('bankId', 'country', 'supplierId'));
    }

    // To show processing Order
    public function processingOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'processing')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.processing_at', 'orders.processing_by')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })

                ->editColumn('processedOrderDate', function ($row) {
                    $dateString = ($row->processing_at);
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $timestamp->isoFormat('Do MMMM, YYYY h:mm:ss A');
                    return $formattedDate;
                })
                ->editColumn('processedBy', function ($row) {
                    $processBy = ($row->processing_by);
                    $processByName = DB::table('users')->where('id', $row->processing_by)->select(DB::raw('CONCAT(IFNULL(users.first_name,"")," ",IFNULL(users.last_name,"")) as full_name'))->value('full_name');
                    return $processByName;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate', 'processedBy', 'processedOrderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.processing_order', compact('bankId', 'country', 'supplierId'));
    }


    // To show query Order
    public function queryOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'query')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.query_at', 'orders.query_by')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })

                ->editColumn('queryOrderDate', function ($row) {
                    $dateString = ($row->query_at);
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $timestamp->isoFormat('Do MMMM, YYYY h:mm:ss A');
                    return $formattedDate;
                })
                ->editColumn('queryBy', function ($row) {
                    $processByName = DB::table('users')->where('id', $row->query_by)->select(DB::raw('CONCAT(IFNULL(users.first_name,"")," ",IFNULL(users.last_name,"")) as full_name'))->value('full_name');
                    return $processByName;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate', 'queryBy', 'queryOrderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.queried_order', compact('bankId', 'country', 'supplierId'));
    }


    // To show completed Order
    public function completedOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'completed')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.completed_at', 'orders.completed_by')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })

                ->editColumn('completedOrderDate', function ($row) {
                    $dateString = ($row->completed_at);
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $timestamp->isoFormat('Do MMMM, YYYY h:mm:ss A');
                    return $formattedDate;
                })
                ->editColumn('completedBy', function ($row) {
                    $processByName = DB::table('users')->where('id', $row->completed_by)->select(DB::raw('CONCAT(IFNULL(users.first_name,"")," ",IFNULL(users.last_name,"")) as full_name'))->value('full_name');
                    return $processByName;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate', 'completedBy', 'completedOrderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.completed_order', compact('bankId', 'country', 'supplierId'));
    }


    // To show delivered Order
    public function deliveredOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'delivered')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }


        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.delivered_at', 'orders.delivered_by')
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })

                ->editColumn('deliveredOrderDate', function ($row) {
                    $dateString = ($row->delivered_at);
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $timestamp->isoFormat('Do MMMM, YYYY h:mm:ss A');
                    return $formattedDate;
                })
                ->editColumn('deliveredBy', function ($row) {
                    $processByName = DB::table('users')->where('id', $row->delivered_by)->select(DB::raw('CONCAT(IFNULL(users.first_name,"")," ",IFNULL(users.last_name,"")) as full_name'))->value('full_name');
                    return $processByName;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate', 'deliveredBy', 'deliveredOrderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.delivered_order', compact('bankId', 'country', 'supplierId'));
    }


    // To show cancel Order
    public function cancelOrder(Request $request)
    {
        $datam = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->where('orders.order_status', 'cancel')
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->leftjoin('products as country', 'orders.country_id', 'country.id');

        if ($request->customer_type) {
            $datam->where('orders.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('orders.bank_id', $request->customer_id);
        }

        if ($request->country) {
            $datam->where('orders.country_id', $request->country);
        }

        if ($request->supplier_id) {
            $datam->where('orders.supplier_id', $request->supplier_id);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
        }

        $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.cancel_at', 'orders.cancel_by', 'orders.cancel_note',)
            ->orderBy('orders.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = ' <a class="btn btn-outline-success btn-xs" href="' . route('order.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('orderStatus', function ($row) {
                    if ($row->cancel_status == 1) {
                        return '<span class="badge badge-danger">Canceled</span>';
                    } elseif ($row->delivered_status == 1) {
                        return '<span class="badge badge-success">Delivered</span>';
                    } elseif ($row->completed_status == 1) {
                        return '<span class="badge badge-warning">Completed</span>';
                    } elseif ($row->query_status == 1) {
                        return '<span class="badge badge-primary">queried</span>';
                    } elseif ($row->processing_status == 1) {
                        return '<span class="badge badge-warning">Processing</span>';
                    } elseif ($row->pending_status == 1) {
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                })
                ->editColumn('orderDate', function ($row) {
                    $dateString = ($row->order_date);
                    $timestamp = strtotime($dateString);
                    $formattedDate = date('jS F, Y', $timestamp);
                    return $formattedDate;
                })

                ->editColumn('cancelOrderDate', function ($row) {
                    $dateString = ($row->cancel_at);
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
                    $formattedDate = $timestamp->isoFormat('Do MMMM, YYYY h:mm:ss A');
                    return $formattedDate;
                })
                ->editColumn('cancelBy', function ($row) {
                    $processByName = DB::table('users')->where('id', $row->cancel_by)->select(DB::raw('CONCAT(IFNULL(users.first_name,"")," ",IFNULL(users.last_name,"")) as full_name'))->value('full_name');
                    return $processByName;
                })
                ->rawColumns(['details', 'orderStatus', 'orderDate', 'cancelBy', 'cancelOrderDate'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Order::order.cancel_order', compact('bankId', 'country', 'supplierId'));
    }

    // To show Bank create page
    public function create()
    {
        $addPage = "Create Order";
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $supplierId = ['' => 'Select Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $editStatus = false;
        $branch = ['' => 'At first select a Bank'];
        return view("Order::order.create", compact('addPage', 'keyPersonnel', 'country', 'bankId', 'supplierId', 'editStatus', 'branch'));
    }

    //  To store Order
    public function store(Request $request)
    {


        DB::beginTransaction();
        try {
            if ($request->file('attachment')) {
                $attachment = $request->file('attachment');
                $destinationPath = public_path('backend/images/order_attachment');
                $attachmentName = uniqid() . '_' . $attachment->getClientOriginalName();
                $attachment->move($destinationPath, $attachmentName);
                $attachmentNameArray = [$attachmentName];
                $attachmentNameJson = json_encode($attachmentNameArray);
            } else {
                $attachmentNameJson = null;
            }

            // Order ID Generate
            $lastOrder = DB::table('orders')->orderBy('id', 'desc')->first();
            if ($lastOrder) {
                $lastOrderNo = $lastOrder->order_invoice_no;
                $newOrderInvoiceNo = '#' . str_pad((int) substr($lastOrderNo, 1) + 1, 8, '0', STR_PAD_LEFT);
            } else {
                $newOrderInvoiceNo = '#00000001';
            }

            $order = new Order();
            $order->company_name = $request->company_name;
            $order->country_id = $request->country;
            $order->order_date = date('d-m-Y');
            $order->order_invoice_no = $newOrderInvoiceNo;
            $order->cm_email = $request->email;
            $order->cm_reg_no = $request->company_reg_no;
            $order->cm_website = $request->website;
            $order->cm_phone = $request->phone_no;
            $order->cm_address = $request->address;
            $order->cm_note = $request->note;
            $order->customer_type = $request->customer_type;
            $order->attachment = $attachmentNameJson;
            $order->order_status = 'pending';
            if ($request->customer_type == 'bank' || $request->customer_type == 'company') {
                $order->bank_id = $request->bank_id;
            } else if ($request->customer_type == 'branch') {
                $order->bank_id = $request->branch_id;
            }
            $order->bank_reference = $request->bank_reference;
            $order->key_personnel_id = $request->key_personnel;
            $order->supplier_id = $request->supplier_id;
            $order->selling_price = $request->selling_price;
            $order->supplier_reference = $request->supplier_reference;
            $order->buying_price = $request->buying_price;
            $order->profit = ($request->selling_price - $request->buying_price);
            $order->pending_status = 1;
            $order->is_generate_invoice = 0;
            $order->created_at = Carbon::now();
            $order->created_by = Auth::id();
            $order->save();

            $newlyCreatedOrderId = $order->id;
            // Save in order status table
            $order_status = DB::table('order_status')->insertGetId([
                'order_id' => $newlyCreatedOrderId,
                'order_status' => 'pending',
                'created_at' => Carbon::now(),
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);
            if ($request->customer_type == 'bank' || $request->customer_type == 'company') {
                $order->bank_id = $request->bank_id;
                $companiesDetails = DB::table('contacts')->where('id', $request->bank_id)->first();
                $companiesEmail = $companiesDetails->cp_email;
                $companyName = $companiesDetails->full_name;
            } else if ($request->customer_type == 'branch') {
                $order->bank_id = $request->branch_id;
                $companiesDetails = DB::table('contacts')->where('id', $request->branch_id)->first();
                $companiesEmail = $companiesDetails->cp_email;
                $companyName = $companiesDetails->full_name;
            }
            $countryName = DB::table('products')->where('id', $request->country)->value('name');
            // echo "<pre>";
            // print_r($companiesEmail);
            // exit();
            
            DB::commit();
            Session::flash('success', "Order Created Successfully");
            return redirect()->route('all.order');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show Bank edit page
    public function edit($id)
    {
        $orderDataDetails = DB::table('orders')
            ->where('id', $id)
            ->first();

        $customer = '';
        $branchId = [];
        $branch = ['' => 'At first select a Bank'];

        if ($orderDataDetails->customer_type == 'bank') {
            $customer = $orderDataDetails->bank_id;
        } else if ($orderDataDetails->customer_type == 'company') {
            $customer = $orderDataDetails->bank_id;
        } else if ($orderDataDetails->customer_type == 'branch') {
            $customer = DB::table('contacts as branch')
                ->where('branch.id', $orderDataDetails->bank_id)
                ->leftjoin('contacts as customer', 'branch.bank_id', 'customer.id')
                ->value('customer.id');
            $branchId = $orderDataDetails->bank_id;
        }
        $editPage = "Edit Order";
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();

        $supplierId = ['' => 'Select Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();

        if ($orderDataDetails->customer_type == 'bank') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($orderDataDetails->customer_type == 'company') {
            $bankId = ['' => 'Select Company'] + DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($orderDataDetails->customer_type == 'branch') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
            $branch = ['' => 'At first select a Bank'] + DB::table('contacts as branch')
                ->where('branch.is_trash', 0)
                ->where('branch.type', 2)
                ->where('branch.bank_id', $customer)
                ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        }

        $keyPersonnelPhone = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->where('id', $orderDataDetails->key_personnel_id)
            ->value('cp_phone_no');

        $editStatus = true;
        return view("Order::order.edit", compact('editPage', 'keyPersonnel', 'country', 'bankId', 'supplierId', 'orderDataDetails', 'customer', 'branch', 'editStatus', 'branchId', 'keyPersonnelPhone'));
    }

    // To update bank data
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->file('pending_attachment')) {
                $old_pending_attachment = $request->old_pending_attachment;
                if ($old_pending_attachment) {
                    if (file_exists(public_path('/backend/images/order_attachment/' . $old_pending_attachment))) {
                        unlink(public_path('/backend/images/order_attachment/' . $old_pending_attachment));
                    }
                    $pendingAttachment = $request->file('pending_attachment');
                    $destinationPath = public_path('backend/images/order_attachment');
                    $pendingAttachmentName = uniqid() . '_' . $pendingAttachment->getClientOriginalName();
                    $pendingAttachment->move($destinationPath, $pendingAttachmentName);
                } else {
                    $pendingAttachment = $request->file('pending_attachment');
                    $destinationPath = public_path('backend/images/order_attachment');
                    $pendingAttachmentName = uniqid() . '_' . $pendingAttachment->getClientOriginalName();
                    $pendingAttachment->move($destinationPath, $pendingAttachmentName);
                }
            } else {
                $pendingAttachmentName = $request->old_pending_attachment;
            }

            $order = Order::find($id);
            $order->company_name = $request->company_name;
            $order->country_id = $request->country;
            $order->cm_email = $request->email;
            $order->cm_reg_no = $request->company_reg_no;
            $order->cm_website = $request->website;
            $order->cm_phone = $request->phone_no;
            $order->cm_address = $request->address;
            $order->cm_note = $request->note;
            $order->customer_type = $request->customer_type;
            $order->attachment = $pendingAttachmentName;

            if ($request->customer_type == 'bank' || $request->customer_type == 'company') {
                $order->bank_id = $request->bank_id;
            } else if ($request->customer_type == 'branch') {
                $order->bank_id = $request->branch_id;
            }

            $order->bank_reference = $request->bank_reference;
            $order->key_personnel_id = $request->key_personnel;
            $order->supplier_id = $request->supplier_id;
            $order->selling_price = $request->selling_price;
            $order->supplier_reference = $request->supplier_reference;
            $order->buying_price = $request->buying_price;
            $order->profit = ($request->selling_price - $request->buying_price);
            $order->pending_status = 1;
            $order->is_generate_invoice = 0;
            $order->upated_at = Carbon::now();
            $order->updated_by = Auth::id();
            $order->save();

            $order_status = DB::table('order_status')->where('order_id', $id)->where('order_status', 'pending')->update([
                'upated_at' => Carbon::now(),
                'note' => $request->note,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();
            Session::flash('success', "Order Updated Successfully");
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To destroy Order
    public function destroy($id)
    {
        Order::where('id', $id)->update([
            'is_trash' => 1
        ]);
        Session::flash('success', "Order Successfully Removed into Trash ");
        return redirect()->back();
    }

    // To show order details page
    public function orderDetails($id)
    {
        $orderDataDetails = DB::table('orders')
            ->where('orders.id', $id)
            ->leftjoin('products as country', 'orders.country_id', 'country.id')
            ->leftjoin('key_personnel', 'orders.key_personnel_id', 'key_personnel.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->select('orders.*', 'country.name as country_name', 'key_personnel.first_name as key_personnel_name', 'key_personnel.cp_phone_no as key_personnel_phone', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"(Mobile: ",IFNULL(supplier.cp_phone_no,""),")") as supplier_name'))
            ->first();

        $customer = '';
        $branchId = '';
        $branch = ['' => 'At first select a Bank'];

        if ($orderDataDetails->customer_type == 'bank') {
            $customer = DB::table('contacts as bank')
                ->where('bank.id', $orderDataDetails->bank_id)
                ->select(DB::raw('CONCAT(IFNULL(bank.full_name,""),"(Mobile: ",IFNULL(bank.cp_phone_no,""),")") as full_name'))
                ->value('contacts.full_name');
            $branchId = 'Head Office';
        } else if ($orderDataDetails->customer_type == 'company') {
            $customer = DB::table('contacts as company')
                ->where('company.id', $orderDataDetails->bank_id)
                ->select(DB::raw('CONCAT(IFNULL(company.full_name,""),"(Mobile: ",IFNULL(company.cp_phone_no,""),")") as full_name'))
                ->value('contacts.full_name');
            $branchId = 'Head Office';
        } else if ($orderDataDetails->customer_type == 'branch') {
            $customer = DB::table('contacts as branch')
                ->where('branch.id', $orderDataDetails->bank_id)
                ->leftjoin('contacts as customer', 'branch.bank_id', 'customer.id')
                ->select(DB::raw('CONCAT(IFNULL(customer.full_name,""),"(Mobile: ",IFNULL(customer.cp_phone_no,""),")") as full_name'))
                ->value('customer.full_name');
            $branchId = DB::table('contacts as branch')
                ->where('branch.id', $orderDataDetails->bank_id)
                ->select(DB::raw('CONCAT(IFNULL(branch.full_name,""),"(Mobile: ",IFNULL(branch.cp_phone_no,""),")") as full_name'))
                ->value('contacts.full_name');
        }
        $editPage = "Edit Order";
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();

        $supplierId = ['' => 'Select Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();

        if ($orderDataDetails->customer_type == 'bank') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($orderDataDetails->customer_type == 'company') {
            $bankId = ['' => 'Select Company'] + DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($orderDataDetails->customer_type == 'branch') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
            $branch = ['' => 'At first select a Bank'] + DB::table('contacts as branch')
                ->where('branch.is_trash', 0)
                ->where('branch.type', 2)
                ->where('branch.bank_id', $customer)
                ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        }

        $keyPersonnelPhone = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->where('id', $orderDataDetails->key_personnel_id)
            ->value('cp_phone_no');

        $editStatus = true;

        $order_status_timeline = DB::table('orders')
            ->where('orders.id', $id)
            ->leftJoin('order_status', 'order_status.order_id', 'orders.id')
            ->leftJoin('users', 'order_status.created_by', 'users.id')
            ->select('order_status.id', DB::raw('CONCAT(IFNULL(users.first_name,"")," ", IFNULL(users.last_name,""),"") as full_name'), 'order_status.order_status', 'order_status.note', 'order_status.created_at as order_time')
            ->get();
        // echo "<pre>";
        // print_r($order_status_timeline);
        // exit();



        return view("Order::order.order_details", compact('editPage', 'keyPersonnel', 'country', 'bankId', 'supplierId', 'orderDataDetails', 'customer', 'branch', 'editStatus', 'branchId', 'keyPersonnelPhone', 'order_status_timeline'));
    }


    // To update bank data
    public function orderAttachmentUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if ($request->file('attachment')) {
                $attachment = $request->file('attachment');
                $destinationPath = public_path('backend/images/order_attachment');
                $attachmentName = uniqid() . '_' . $attachment->getClientOriginalName();
                $attachment->move($destinationPath, $attachmentName);
                $attachmentNameArray = [$attachmentName];
                $oldAttachmentExist = DB::table('orders')->where('id', $id)->value('attachment');
                if (!empty($oldAttachmentExist)) {
                    $oldAttachmentJson = json_decode(DB::table('orders')->where('id', $id)->value('attachment'));
                    $attachmentNameArray = array_merge($oldAttachmentJson, $attachmentNameArray);
                }
                $attachmentNameJson = json_encode($attachmentNameArray);

                $order = Order::find($id);
                $order->attachment = $attachmentNameJson;
                $order->updated_by = Auth::id();
                $order->save();
                DB::commit();
                Session::flash('success', "Attachment Updated Successfully");
                return redirect()->back();
            } else {
                Session::flash('danger', "Please give attachment");
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To update order status
    public function orderStatusUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            $order->order_status = $request->order_status;
            if ($request->order_status == 'processing') {
                $order->processing_note = $request->note;
                $order->processing_at = Carbon::now();
                $order->processing_by = Auth::id();
                $order->processing_status = 1;
            } else if ($request->order_status == 'query') {
                $order->query_note = $request->note;
                $order->query_at = Carbon::now();
                $order->query_by = Auth::id();
                $order->query_status = 1;
            } else if ($request->order_status == 'cancel') {
                $order->cancel_note = $request->note;
                $order->cancel_at = Carbon::now();
                $order->cancel_by = Auth::id();
                $order->cancel_status = 1;
            } else if ($request->order_status == 'completed') {
                $order->completed_note = $request->note;
                $order->completed_at = Carbon::now();
                $order->completed_by = Auth::id();
                $order->completed_status = 1;
            } else if ($request->order_status == 'delivered') {
                $order->delivered_note = $request->note;
                $order->delivered_at = Carbon::now();
                $order->delivered_by = Auth::id();
                $order->delivered_status = 1;
            }
            $order->updated_by = Auth::id();
            $order->save();

            $order_status = DB::table('order_status')->insertGetId([
                'order_id' => $id,
                'order_status' => $request->order_status,
                'created_at' => Carbon::now(),
                'note' => $request->note,
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('success', "Order Status Updated Successfully");
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // Get Supplier Wise Price depends on Supplier Name
    public function getSupplierWisePrice($supplierId, $countryId)
    {
        $data = DB::table('supplier_pricing')->where('dealer_id', $supplierId)->where('product_id', $countryId)->value('price');
        // dd($data);
        return response()->json($data);
    }

    // Get Customer Wise Price depends on Customer Name
    public function getCustomerWisePrice($customerId, $countryId)
    {
        $individualPrice = DB::table('customer_pricing')->where('customer_id', $customerId)->where('product_id', $countryId)->value('price');
        $attachPrice = DB::table('pricing')->where('product_id', $countryId)->value('price');
        // Store the price in $data
        $data = $individualPrice ?: $attachPrice;
        return response()->json($data);
    }

    // get section dependent on class
    public function getBank(Request $request)
    {
        $data = DB::table('contacts as branch')
            ->where('branch.is_trash', 0)
            ->where('branch.type', 2)
            ->where('branch.bank_id', $request->bankId)
            ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
            ->get();
        return response()->json($data);
    }

    // get section dependent on class
    public function getCustomer(Request $request)
    {
        if ($request->customerType == 'bank' || $request->customerType == 'branch') {
            $data = DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->get();
        } else if ($request->customerType == 'company') {
            $data = DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->get();
        }

        return response()->json($data);
    }

    // get independent customer
    public function getCustomerIndependent(Request $request)
    {
        if ($request->customerType == 'bank') {
            $data = DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->get();
        } else if ($request->customerType == 'branch') {
            $data = DB::table('contacts as branch')
                ->where('branch.is_trash', 0)
                ->where('branch.type', 2)
                ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
                ->get();
        } else if ($request->customerType == 'company') {
            $data = DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->get();
        }

        return response()->json($data);
    }



    // get key Personnel dependent on Customer
    public function getOrderKeypersonnel(Request $request)
    {
        $data = DB::table('contacts as customer')
            ->where('customer.id', $request->keyPersonnelSearchId)
            ->leftjoin('key_personnel', 'customer.key_personnel_id', 'key_personnel.id')
            ->where('key_personnel.is_trash', 0)
            ->select('key_personnel.id', 'key_personnel.cp_phone_no as phone')
            ->first();

        return response()->json($data);
    }
}
