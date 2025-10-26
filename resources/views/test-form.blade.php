<!DOCTYPE html>
<html>
<head>
    <title>Test Form Submission</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Form Submission</h1>
    
    <form id="test-form" action="{{ route('test.form') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="Test Name">
        </div>
        
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="test@example.com">
        </div>
        
        <div>
            <label for="file">File:</label>
            <input type="file" id="file" name="file">
        </div>
        
        <button type="submit">Submit</button>
    </form>
    
    <div id="result"></div>
    
    <script>
        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').innerHTML = '<p>Error: ' + error.message + '</p>';
            });
        });
    </script>
</body>
</html>