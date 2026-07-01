<!DOCTYPE html>
<html>
<head>
    <title>Product {{ ucfirst($action) }}</title>
</head>
<body>
    <h1>Product {{ ucfirst($action) }}</h1>
    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p><strong>Description:</strong> {{ $product->description }}</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
</body>
</html>
