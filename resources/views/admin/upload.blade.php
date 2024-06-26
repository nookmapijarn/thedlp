<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload ZIP File</title>
</head>
<body>
    <h1>Upload ZIP File</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <form action="{{ route('zip.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="zip_file">ZIP File:</label>
            <input type="file" name="zip_file" id="zip_file" required>
        </div>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
