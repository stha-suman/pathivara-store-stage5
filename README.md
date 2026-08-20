# Pathivara Store — Stage 4

Stage 4 adds a browser-based admin dashboard.

## Admin
Open `admin/index.html`

Demo login:
- Username: `admin`
- Password: `pathivara123`

## Dashboard features
- Product count and low-stock overview
- Recent orders
- Product catalog management
- Add/edit/delete products
- Price and stock management
- Product image URL management
- Order list and search
- Order status: New, Confirmed, Processing, Ready, Delivered, Cancelled
- Order detail view
- WhatsApp contact shortcut
- Store settings
- JSON data export
- Demo data reset

## Important limitation
This is a frontend/localStorage prototype. The demo admin password is NOT secure and data is stored only in the browser. Do not use it as a production admin system.

For production Stage 5, use a real backend/database, hashed authentication, protected admin APIs, server-side validation, image storage and role-based access.

## Stage 4.1 bug fix
Fixed the CSS `hidden` attribute issue that caused the order-details modal to appear immediately and prevented the login/dashboard views from switching correctly.
