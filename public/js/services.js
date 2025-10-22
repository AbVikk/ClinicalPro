/**
 * Service Management JavaScript Functions
 */

// Function to fetch all services via AJAX
function fetchServices(callback) {
    $.ajax({
        url: '/admin/services/api',
        method: 'GET',
        success: function(response) {
            if (typeof callback === 'function') {
                callback(null, response);
            }
        },
        error: function(xhr, status, error) {
            if (typeof callback === 'function') {
                callback(error, null);
            }
        }
    });
}

// Function to populate a select dropdown with services
function populateServiceDropdown(selectElementId) {
    fetchServices(function(error, services) {
        if (error) {
            console.error('Error fetching services:', error);
            return;
        }
        
        var selectElement = $('#' + selectElementId);
        selectElement.empty();
        selectElement.append('<option value="">- Select Service -</option>');
        
        services.forEach(function(service) {
            selectElement.append(
                '<option value="' + service.id + '" data-price="' + service.price_amount + '">' +
                service.service_name + ' (' + service.price_currency + ' ' + parseFloat(service.price_amount).toFixed(2) + ')' +
                '</option>'
            );
        });
        
        // Refresh the selectpicker if it's being used
        if (typeof selectElement.selectpicker === 'function') {
            selectElement.selectpicker('refresh');
        }
    });
}

// Function to get service details by ID
function getServiceById(serviceId, callback) {
    fetchServices(function(error, services) {
        if (error) {
            if (typeof callback === 'function') {
                callback(error, null);
            }
            return;
        }
        
        var service = services.find(function(s) {
            return s.id == serviceId;
        });
        
        if (typeof callback === 'function') {
            callback(null, service);
        }
    });
}

// Function to calculate total amount based on selected service
function calculateTotalAmount(serviceId, callback) {
    getServiceById(serviceId, function(error, service) {
        if (error) {
            if (typeof callback === 'function') {
                callback(error, null);
            }
            return;
        }
        
        if (service) {
            if (typeof callback === 'function') {
                callback(null, parseFloat(service.price_amount));
            }
        } else {
            if (typeof callback === 'function') {
                callback('Service not found', null);
            }
        }
    });
}

// Document ready function
$(document).ready(function() {
    // If there's a service dropdown on the page, populate it
    if ($('#service_id').length > 0) {
        populateServiceDropdown('service_id');
    }
    
    // Listen for changes on service dropdown and update price display
    $(document).on('change', '#service_id', function() {
        var serviceId = $(this).val();
        var priceDisplay = $('#service_price_display');
        
        if (serviceId && priceDisplay.length > 0) {
            calculateTotalAmount(serviceId, function(error, amount) {
                if (!error && amount !== null) {
                    priceDisplay.text('₦' + amount.toFixed(2));
                } else {
                    priceDisplay.text('₦0.00');
                }
            });
        }
    });
});