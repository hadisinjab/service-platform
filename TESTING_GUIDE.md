# Service Platform - Testing Guide

## Quick Setup

### 1. Run the Quick Test Seeder
```bash
php artisan db:seed --class=QuickTestSeeder
```

### 2. Start the Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Test Accounts

### Client Account
- **Email:** client@test.com
- **Password:** password
- **Role:** Client

### Provider Account
- **Email:** provider@test.com
- **Password:** password
- **Role:** Provider

## Test Data Created

### Services
- 1 House Cleaning Service ($80.00)

### Orders (5 different statuses)
1. **Weekly House Cleaning** - Pending
2. **Kitchen Sink Repair** - Accepted
3. **Electrical Outlet Installation** - In Progress
4. **Deep Carpet Cleaning** - Completed (with review)
5. **Bathroom Renovation** - Rejected

## Testing Steps

### Step 1: Login as Provider
1. Go to: http://localhost:8000/login
2. Login with: provider@test.com / password
3. You should see the provider dashboard

### Step 2: Check Provider Dashboard
1. Navigate to: http://localhost:8000/provider/dashboard
2. Verify statistics are displayed:
   - Recent orders
   - Pending orders count
   - Completed orders count
   - Total earnings
   - Average rating

### Step 3: Check Orders List
1. Navigate to: http://localhost:8000/provider/orders
2. Verify you see 5 orders with different statuses
3. Check that each order shows:
   - Client name and email
   - Service title
   - Status badge
   - Price
   - "View Details" button

### Step 4: Test Order Details
1. Click "View Details" on any order
2. Verify order details page shows:
   - Order title and ID
   - Client information
   - Service details
   - Price
   - Description
   - Notes (if any)
   - Status update form (for provider)

### Step 5: Test Status Updates
1. Open a "Pending" order
2. In the "Update Status" section:
   - Select "Accept Order"
   - Add a note: "Order accepted, will start tomorrow"
   - Click "Update Status"
3. Verify status changes to "Accepted"
4. Repeat for other status transitions:
   - Accepted → In Progress
   - In Progress → Completed

### Step 6: Test Quick Actions
1. In order details, check "Quick Actions" section
2. Test "Email Client" button (should open email client)
3. Test "Call Client" button (if phone number exists)

### Step 7: Login as Client
1. Open new browser window/tab
2. Go to: http://localhost:8000/login
3. Login with: client@test.com / password

### Step 8: Check Client Orders
1. Navigate to: http://localhost:8000/client/orders
2. Verify you see the same 5 orders
3. Check that status updates are reflected

### Step 9: Test Order Details (Client View)
1. Click on any order
2. Verify you can see:
   - Order details
   - Provider notes (if any)
   - Status updates
   - Review option (for completed orders)

### Step 10: Test Review System
1. Open the "Completed" order
2. Click "Write Review"
3. Give 5 stars and write a comment
4. Submit the review
5. Login as provider and verify review appears

## Expected Results

### Provider Dashboard
- Should show 1 pending order
- Should show 1 completed order
- Should show total earnings of $80.00
- Should show average rating of 5.0

### Orders List
- Should display 5 orders
- Status badges should be color-coded
- "Action Required" indicator for pending orders

### Order Details
- All information should be displayed correctly
- Status update form should work
- Quick actions should function
- Timeline should show order history

### Status Transitions
- Pending → Accepted → In Progress → Completed
- Pending → Rejected
- All transitions should work smoothly

## Troubleshooting

### If orders don't appear:
```bash
php artisan db:seed --class=QuickTestSeeder
```

### If login fails:
```bash
php artisan migrate:fresh --seed
```

### If routes don't work:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### Check logs for errors:
```bash
tail -f storage/logs/laravel.log
```

## Features to Test

### ✅ Provider Features
- [ ] View dashboard with statistics
- [ ] View orders list
- [ ] View order details
- [ ] Update order status
- [ ] Add provider notes
- [ ] Quick actions (email, call)
- [ ] View client reviews

### ✅ Client Features
- [ ] View orders list
- [ ] View order details
- [ ] See status updates
- [ ] Write reviews
- [ ] View provider notes

### ✅ System Features
- [ ] Status transitions
- [ ] Notifications
- [ ] Review system
- [ ] Responsive design
- [ ] Navigation between pages

## Success Criteria

- All status updates work correctly
- All pages load without errors
- Navigation between pages works
- Data is displayed correctly
- Forms submit successfully
- Responsive design works on different screen sizes 
