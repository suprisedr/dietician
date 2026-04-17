<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Food Diary</title>
<style>
    :root {
        --bg: #f3e9e9;
        --green: #2d5a43;
        --green-dark: #1a3d2b;
        --green-light: #4a7a60;
        --border: #c8ddd6;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        background-color: #dcdcdc;
        font-family: Georgia, 'Times New Roman', serif;
        min-height: 100vh;
        padding: 24px 16px 48px;
        color: var(--green);
    }
    .card {
        background-color: var(--bg);
        max-width: 720px;
        margin: 0 auto;
        padding: 36px 36px 28px;
        border-radius: 6px;
        box-shadow: 0 10px 30px rgba(0,0,0,.13);
    }
    /* Header */
    header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid var(--green);
        padding-bottom: 18px;
        margin-bottom: 26px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .title-section h1 {
        font-size: 2.4rem;
        font-weight: normal;
        line-height: .95;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .title-section h1 span {
        display: block;
        font-style: italic;
        font-size: 1.9rem;
        text-transform: capitalize;
        letter-spacing: 0;
    }
    .meta-info { font-weight: bold; text-align: right; font-size: .88rem; }
    .meta-info label { display: block; margin-bottom: 8px; }
    .meta-info input[type="date"] {
        font-family: inherit;
        border: none;
        border-bottom: 1px solid var(--green);
        background: transparent;
        color: var(--green);
        font-size: .88rem;
        padding: 2px 4px;
        outline: none;
        width: 160px;
    }
    /* Meal grid */
    .meal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 22px;
    }
    @media (max-width: 520px) { .meal-grid { grid-template-columns: 1fr; } }
    .meal-box { border: 1px solid var(--green); border-radius: 4px; overflow: hidden; }
    .meal-label {
        background: var(--green);
        color: #fff;
        padding: 5px 14px;
        font-size: .78rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-weight: 700;
    }
    textarea {
        width: 100%;
        height: 80px;
        padding: 8px 10px;
        background: rgba(255,255,255,.3);
        border: none;
        font-family: Georgia, serif;
        font-size: .87rem;
        color: var(--green-dark);
        resize: vertical;
        outline: none;
        line-height: 1.5;
    }
    textarea::placeholder { color: #9ca3af; }
    /* Reflection */
    .reflection { margin-top: 4px; }
    .reflection-label { font-weight: bold; font-size: .9rem; margin-bottom: 8px; display: block; }
    .rating-row { display: flex; gap: 4px; align-items: center; margin-bottom: 18px; }
    .star-label { cursor: pointer; font-size: 2rem; color: #d1d5db; transition: color .13s, transform .13s; display: inline-block; line-height: 1; user-select: none; }
    .star-radio { display: none; }
    .improvement-textarea {
        width: 100%;
        height: 90px;
        border: 1.5px solid var(--green);
        border-radius: 4px;
        background: rgba(255,255,255,.3);
        padding: 8px 10px;
        font-family: Georgia, serif;
        font-size: .87rem;
        color: var(--green-dark);
        resize: vertical;
        outline: none;
    }
    /* Submit */
    .submit-btn {
        display: block;
        width: 100%;
        margin-top: 22px;
        padding: 13px;
        background: var(--green);
        color: #fff;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-size: 1rem;
        font-weight: 700;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        letter-spacing: .05em;
    }
    .submit-btn:hover { background: var(--green-dark); }
    .error-msg { background: #fee2e2; color: #b91c1c; border-radius: 5px; padding: .75rem 1rem; font-size: .85rem; margin-bottom: 1rem; font-family: Arial, sans-serif; }
    .error-msg ul { padding-left: 1.1rem; margin: 0; }
    .patient-name { color: var(--green-light); font-size: .82rem; margin-top: 4px; }
</style>
</head>
<body>

<div class="card">
    <header>
        <div class="title-section">
            <h1><span>Daily food</span> diary</h1>
            @if($diary->patient)
                <p class="patient-name">{{ $diary->patient->name }}</p>
            @endif
        </div>
        <div class="meta-info">
            <label>Date</label>
        </div>
    </header>

    @if($errors->any())
        <div class="error-msg">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('food-diary.patient-submit', $token) }}">
        @csrf

        {{-- Date --}}
        <div style="margin-bottom:18px;font-family:Arial,sans-serif">
            <label style="font-size:.82rem;font-weight:700;display:block;margin-bottom:5px;color:var(--green)">
                Date <span style="color:#dc2626">*</span>
            </label>
            <input type="date" name="diary_date"
                   value="{{ old('diary_date', date('Y-m-d')) }}"
                   required
                   style="padding:.45rem .7rem;border:1px solid var(--border);border-radius:5px;font-size:.88rem;background:#fff;color:var(--green-dark);font-family:Arial,sans-serif">
        </div>

        {{-- Meal slots --}}
        <div class="meal-grid">
            @foreach(['breakfast' => 'Breakfast', 'snack1' => 'Snack (Morning)', 'lunch' => 'Lunch', 'snack2' => 'Snack (Afternoon)', 'supper' => 'Supper', 'snack3' => 'Snack (Evening)'] as $slot => $label)
            <div class="meal-box">
                <div class="meal-label">{{ $label }}</div>
                <textarea name="{{ $slot }}" placeholder="What did you eat?">{{ old($slot) }}</textarea>
            </div>
            @endforeach
        </div>

        {{-- Rating --}}
        <div class="reflection">
            <span class="reflection-label">Rate your day: <span style="font-size:.8rem;font-weight:400;color:#9ca3af;font-family:Arial,sans-serif">(optional)</span></span>
            <div class="rating-row" id="star-picker">
                @for($i = 1; $i <= 5; $i++)
                    <input class="star-radio" type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                           {{ old('rating') == $i ? 'checked' : '' }}>
                    <label class="star-label" for="star{{ $i }}" data-val="{{ $i }}">&#9733;</label>
                @endfor
                <span id="rating-label" style="font-size:.8rem;font-weight:700;color:var(--green);font-family:Arial,sans-serif;margin-left:8px;min-width:60px;text-transform:uppercase;letter-spacing:.04em;align-self:center;opacity:0;transition:opacity .15s"></span>
            </div>
        </div>

        {{-- Improvement --}}
        <div style="margin-top:16px">
            <span class="reflection-label">What can I improve on?</span>
            <textarea class="improvement-textarea" name="improvement"
                      placeholder="Your reflections...">{{ old('improvement') }}</textarea>
        </div>

        <button type="submit" class="submit-btn">Submit My Food Diary</button>
    </form>
</div>

<script>
(function(){
    var ratingLabels = ['','Poor','Fair','Good','Great','Excellent'];
    var picker = document.getElementById('star-picker');
    if (!picker) return;
    var stars = picker.querySelectorAll('label.star-label');
    var textEl = document.getElementById('rating-label');
    var selected = 0;

    function paint(n) {
        stars.forEach(function(l) {
            var v = parseInt(l.dataset.val);
            l.style.color = v <= n ? 'var(--green)' : '#d1d5db';
            l.style.transform = v <= n ? 'scale(1.12)' : 'scale(1)';
        });
        if (textEl) {
            textEl.style.opacity = n ? '1' : '0';
            textEl.textContent = n ? ratingLabels[n] : '';
        }
    }

    var checked = picker.querySelector('input[type="radio"]:checked');
    if (checked) { selected = parseInt(checked.value); paint(selected); }

    stars.forEach(function(l) {
        l.addEventListener('mouseenter', function() { paint(parseInt(l.dataset.val)); });
        l.addEventListener('mouseleave', function() { paint(selected); });
    });

    picker.querySelectorAll('input[type="radio"]').forEach(function(inp) {
        inp.addEventListener('change', function() { selected = parseInt(inp.value); paint(selected); });
    });
})();
</script>

</body>
</html>
