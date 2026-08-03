<x-mail::message>
    <p><strong>{{ $subject }}</strong></p>
    <table cellpadding="5">
        <tbody>
        <tr>
            <td>{!! __("Date") !!}</td>
            <td>{{ $inbox->created_at->format('H:i:s d.m.Y') }}</td>
        </tr>
        <tr>
            <td colspan="2">{!! $inbox->html() !!}</td>
        </tr>
        </tbody>
    </table>
    <x-mail::button :url="$inbox->view_url">
        {{ __('Просмотр') }}
    </x-mail::button>
</x-mail::message>
