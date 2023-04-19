<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Conele')
    <img src="https://www.conele.co/storage/img/2_logo_fondo.png" class="logo1" alt="{{ $slot }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
