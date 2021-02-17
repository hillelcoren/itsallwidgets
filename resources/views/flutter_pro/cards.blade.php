<table>
    <tr>
        <th>Widget</th>
        <th>Developer</th>
        <th>Description</th>
        <th>Tip</th>
        <th>Code Sample</th>
    </tr>

@foreach ($users as $user)
    <tr>
        <td><a href="https://www.youtube.com/watch?v={{ $user->widget_youtube_url }}" target="_blank">{{ $user->widget }}</a></td>
        <td><a href="{{ $user->twitter_url }}" target="_blank">{{ $user->twitterHandle() }}</a>
        <td>{{ $user->widget_description }} [{{ strlen($user->widget_description) }}]</td>
        <td>{{ $user->widget_tip }} [{{ strlen($user->widget_tip) }}]</td>
        <td><pre>{{ $user->widget_code_sample }}</pre> [{{ strlen($user->widget_code_sample) }}]</td>
    </tr>
@endforeach

</table>
