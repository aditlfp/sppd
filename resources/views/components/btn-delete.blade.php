<form {{ $attributes->merge(['action' => '#', 'method' => 'POST'])}} >
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'bg-red-500 p-2 rounded-sm text-white hover:bg-red-400 hover:text-red-700 transition-all ease-in-out duration-150' ])}}>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7ZM13.4142 13.9997L15.182 12.232L13.7678 10.8178L12 12.5855L10.2322 10.8178L8.81802 12.232L10.5858 13.9997L8.81802 15.7675L10.2322 17.1817L12 15.4139L13.7678 17.1817L15.182 15.7675L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
    </button>
</form>
