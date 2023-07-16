<form {{ $attributes }} {{ $setMethod() }}>
    @method("$method")
    @csrf
    {{ $slot }}
</form>