<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyBooking;
use App\Models\Contract;
use App\Models\Container;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of daily bookings
     */
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $status = $request->get('status', 'all');

        $query = DailyBooking::with(['contract', 'container', 'customer', 'driver'])
            ->whereDate('booking_date', $date);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(50);

        $bookingStats = [
            'total' => DailyBooking::whereDate('booking_date', $date)->count(),
            'pending' => DailyBooking::whereDate('booking_date', $date)->where('status', 'pending')->count(),
            'confirmed' => DailyBooking::whereDate('booking_date', $date)->where('status', 'confirmed')->count(),
            'delivered' => DailyBooking::whereDate('booking_date', $date)->where('status', 'delivered')->count(),
            'cancelled' => DailyBooking::whereDate('booking_date', $date)->where('status', 'cancelled')->count(),
        ];

        return view('bookings.index', compact('bookings', 'bookingStats', 'date', 'status'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $contracts = Contract::with(['customer', 'type'])->get();
        $containers = Container::where('status', 'available')->with('type')->get();
        $customers = Customer::all();
        $drivers = User::all();

        return view('bookings.create', compact('contracts', 'containers', 'customers', 'drivers'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'contract_id' => 'required|exists:contracts,id',
            'container_id' => 'required|exists:containers,id',
            'customer_id' => 'required|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'driver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
        ]);

        try {
            $booking = DailyBooking::create($validated);

            // Update container status to 'in_use'
            $container = Container::findOrFail($validated['container_id']);
            $container->update(['status' => 'in_use']);

            return redirect()->route('bookings.index')
                ->with('success', __('Booking created successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', __('Failed to create booking. Please try again.'));
        }
    }

    /**
     * Display the specified booking
     */
    public function show(DailyBooking $booking)
    {
        $booking->load(['contract', 'container', 'customer', 'driver']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit(DailyBooking $booking)
    {
        $contracts = Contract::with(['customer', 'type'])->get();
        $containers = Container::with('type')->get();
        $customers = Customer::all();
        $drivers = User::all();

        return view('bookings.edit', compact('booking', 'contracts', 'containers', 'customers', 'drivers'));
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, DailyBooking $booking)
    {
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'contract_id' => 'required|exists:contracts,id',
            'container_id' => 'required|exists:containers,id',
            'customer_id' => 'required|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'driver_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,confirmed,delivered,cancelled',
            'notes' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
        ]);

        try {
            $booking->update($validated);

            return redirect()->route('bookings.index')
                ->with('success', __('Booking updated successfully.'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', __('Failed to update booking. Please try again.'));
        }
    }

    /**
     * Remove the specified booking
     */
    public function destroy(DailyBooking $booking)
    {
        try {
            // Update container status back to 'available'
            $container = $booking->container;
            $container->update(['status' => 'available']);

            $booking->delete();

            return redirect()->route('bookings.index')
                ->with('success', __('Booking deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to delete booking. Please try again.'));
        }
    }

    /**
     * Confirm a booking
     */
    public function confirm(DailyBooking $booking)
    {
        try {
            $booking->update(['status' => 'confirmed']);

            return redirect()->route('bookings.index')
                ->with('success', __('Booking confirmed successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to confirm booking. Please try again.'));
        }
    }

    /**
     * Mark booking as delivered
     */
    public function deliver(DailyBooking $booking)
    {
        try {
            $booking->update(['status' => 'delivered']);

            return redirect()->route('bookings.index')
                ->with('success', __('Booking marked as delivered successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to mark booking as delivered. Please try again.'));
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel(DailyBooking $booking)
    {
        try {
            $booking->update(['status' => 'cancelled']);

            // Update container status back to 'available'
            $container = $booking->container;
            $container->update(['status' => 'available']);

            return redirect()->route('bookings.index')
                ->with('success', __('Booking cancelled successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to cancel booking. Please try again.'));
        }
    }
}