<html>
    Hello! This is my report:

    @foreach ($restaurants as $restaurant)

    {{ $restaurant->restaurant_name  }}
    <br>
    <br>

    @endforeach
</html>