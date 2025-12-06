# Testing Subdomain Registration Locally

To test the multitenancy system with subdomains on your local machine, follow these steps:

## 1. Update Your Hosts File

Add entries to your hosts file to simulate subdomains:

### Windows:
1. Open Notepad as Administrator
2. Open the file: `C:\Windows\System32\drivers\etc\hosts`
3. Add these lines at the end:
```
127.0.0.1 demo.localhost
127.0.0.1 island.localhost
127.0.0.1 your-hospital-name.localhost
```

### Mac/Linux:
1. Open terminal
2. Edit the hosts file:
```bash
sudo nano /etc/hosts
```
3. Add these lines:
```
127.0.0.1 demo.localhost
127.0.0.1 island.localhost
127.0.0.1 your-hospital-name.localhost
```

## 2. Update Your .env File

Make sure your `.env` file has the correct APP_URL:

```
APP_URL=http://localhost
```

## 3. Test the Registration Flow

### Hospital Registration:
1. Visit: http://localhost/register/hospital
2. Register a new hospital
3. Note the domain prefix you choose (e.g., "general-hospital")

### User Registration for a Specific Hospital:
1. After adding to hosts file, visit: http://your-domain-prefix.localhost/register
2. Or for existing hospitals:
   - http://demo.localhost/register
   - http://island.localhost/register

## 4. Alternative Testing Method (Without Hosts File)

If you prefer not to modify your hosts file, you can test by passing the hospital domain as a parameter:

1. Visit: http://localhost/register/demo (for demo hospital)
2. Visit: http://localhost/register/island (for island hospital)

## 5. Available Hospitals

Based on your current database, these hospitals are available:

1. **Demo Hospital** - Domain: `demo`
   - URL for testing: http://demo.localhost/register
   - Or: http://localhost/register/demo

2. **Lagos Island General** - Domain: `island`
   - URL for testing: http://island.localhost/register
   - Or: http://localhost/register/island

## 6. Registration Process

1. **Hospital Admin Registration** (for new hospitals):
   - Go to: http://localhost/register/hospital
   - Fill in hospital details
   - Automatically logged in as hospital admin

2. **User Registration** (for existing hospitals):
   - Go to: http://hospital-domain.localhost/register
   - Select the hospital from dropdown
   - Complete registration process
   - Users are automatically associated with the correct hospital

## 7. Verification

After registration, you can verify the multitenancy is working by:

1. Logging in as a user from Hospital A
2. Creating some data (patients, appointments, etc.)
3. Logging out and logging in as a user from Hospital B
4. Confirming you cannot see Hospital A's data

The system uses the `hospital_id` field and global scopes to ensure complete data isolation between hospitals.