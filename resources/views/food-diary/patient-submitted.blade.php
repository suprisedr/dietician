<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Diary Submitted</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #dcdcdc; font-family: Georgia, 'Times New Roman', serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
    .card { background: #f3e9e9; max-width: 480px; width: 100%; padding: 48px 40px; border-radius: 6px; box-shadow: 0 10px 30px rgba(0,0,0,.13); text-align: center; }
    .icon { font-size: 3.5rem; margin-bottom: 1rem; }
    h1 { font-size: 1.6rem; font-weight: normal; color: #2d5a43; margin-bottom: .75rem; text-transform: uppercase; letter-spacing: 2px; }
    p { font-size: .95rem; color: #4a7a60; line-height: 1.65; }
    .date { display: inline-block; margin-top: 1rem; background: #2d5a43; color: #fff; padding: .35rem 1rem; border-radius: 20px; font-size: .82rem; font-family: Arial, sans-serif; font-weight: 700; }
</style>
</head>
<body>
<div class="card">
    <div class="icon">&#x2705;</div>
    <h1>Thank you!</h1>
    <p>Your food diary has been submitted successfully.<br>Your dietician will review it shortly.</p>
    @if($diary->diary_date)
        <span class="date">{{ $diary->diary_date->format('l, d M Y') }}</span>
    @endif
</div>
</body>
</html>
