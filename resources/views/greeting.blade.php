<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Laravel Quickstart - Basic</title>

    <!-- CSS и JavaScript -->
  </head>

  <body>
        <h1>Hello, {{ $name }}</h1>
    <div class="container">
      <nav class="navbar navbar-default">
        <!-- Содержимое Navbar -->
      </nav>
    </div>

    @yield('content')
  </body>
</html>