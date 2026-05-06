{{-- resources/views/components/flash-message.blade.php --}}

@if (session('message'))
    <div id="flash-message" style="background-color: #d4edda; color: #155724; padding: 15px; text-align: center; border-bottom: 1px solid #c3e6cb; position: relative;">
        {{ session('message') }}
        <button onclick="this.parentElement.style.display='none'" style="position: absolute; right: 20px; top: 15px; border: none; background: none; cursor: pointer; color: #155724; font-weight: bold;">×</button>
    </div>
@endif

@if (session('error'))
    <div id="flash-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; text-align: center; border-bottom: 1px solid #f5c6cb; position: relative;">
        {{ session('error') }}
        <button onclick="this.parentElement.style.display='none'" style="position: absolute; right: 20px; top: 15px; border: none; background: none; cursor: pointer; color: #721c24; font-weight: bold;">×</button>
    </div>
@endif
