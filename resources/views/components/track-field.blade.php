@props([
  'position' => '',
  'artist'=> '',
  'title' => '',
  'duration' => '',
  'mix' => ''
])

<label for="track_positions">Position:</label>
<input type="text" name="track_positions[]" id="track_positions" value="{{ $position }}">
<label for="track_artists">Artist:</label>
<input list="artists" name="track_artists[]" id="track_artists" value="{{ $artist }}">
<label for="track_titles">Title:</label>
<input type="text" name="track_titles[]" id="track_titles" value="{{ $title }}">
<label for="track_durations">Duration:</label>
<input type="text" name="track_durations[]" id="track_durations" value="{{ $duration }}">
<label for="track_mixes">Mix:</label>
<input type="text" name="track_mixes[]" id="track_mixes" value="{{ $mix }}">
