# Paystack Webhook Setup Guide

## Overview

This document explains how to set up Paystack webhooks for the ClinicalPro healthcare management system. Webhooks are essential for handling asynchronous events like Dedicated Virtual Account (DVA) deposits, which are crucial for wallet top-ups via bank transfers.

## Webhook Controller

The webhook functionality is implemented in a separate controller to ensure proper security isolation:

- **Controller**: `App\Http\Controllers\PaystackWebhookController`
- **Route**: `POST /paystack/webhook` (public route, no authentication)

## Security Implementation

The webhook controller verifies incoming requests using Paystack's signature verification:

1. Extracts the `x-paystack-signature` header from the request
2. Computes the expected signature using HMAC-SHA512 with your Paystack secret key
3. Compares the signatures to ensure the request originated from Paystack

## Supported Events

The webhook controller currently handles these events:

1. `charge.success` - Successful card payments
2. `transfer.success` - Successful transfers (for DVA deposits)
3. `invoice.success` - Successful invoice payments

## Setup Instructions

### 1. Configure Paystack Dashboard

1. Log in to your Paystack dashboard
2. Navigate to Settings > Developer/API
3. In the Webhooks section, add your webhook URL:
   ```
   https://yourdomain.com/paystack/webhook
   ```
4. For local development, use a tool like ngrok to expose your local server:
   ```
   https://your-ngrok-url.ngrok.io/paystack/webhook
   ```

### 2. Test the Webhook

1. Use the webhook test page at: `/admin/wallet/test-webhook`
2. Trigger test events from your Paystack dashboard
3. Check Laravel logs to verify events are being processed

## Admin Wallet Top-Up

The system includes a dedicated flow for hospital wallet top-ups:

1. **Form**: `/admin/wallet/topup` - Shows the top-up form
2. **Initialize**: `POST /admin/wallet/topup/initialize` - Initializes the payment
3. **Verify**: `GET /admin/wallet/topup/verify` - Verifies the payment after completion

## Webhook vs. Callback

It's important to understand the difference between these two mechanisms:

| Aspect | Webhook | Callback |
|--------|---------|----------|
| **Initiated By** | Paystack's server | User's browser |
| **Authentication** | Signature verification | Laravel auth middleware |
| **Purpose** | Asynchronous event notifications | Browser-based payment completion |
| **Events Handled** | All Paystack events | Only browser-initiated payments |

## Troubleshooting

### Common Issues

1. **Webhook not receiving events**
   - Check that your webhook URL is correctly configured in Paystack dashboard
   - Ensure your server is accessible from the internet
   - Verify that your Paystack secret key is correctly set in `.env`

2. **Signature verification failing**
   - Confirm that `PAYSTACK_SECRET_KEY` in `.env` matches your Paystack dashboard
   - Check that no extra whitespace or characters are in the key

3. **Events not being processed**
   - Check Laravel logs for error messages
   - Ensure the event type is supported in the webhook controller

### Logging

All webhook events are logged using Laravel's logging system. Check `storage/logs/laravel.log` for:
- Received events with their payloads
- Processing results
- Error messages

## Extending Webhook Functionality

To add support for additional Paystack events:

1. Add a new case in the `handleWebhook` method's switch statement
2. Create a corresponding handler method
3. Implement the business logic for the event

Example:
```php
case 'subscription.success':
    return $this->handleSubscriptionSuccess($payload);
```