<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Recipe: {{ $recipe->name }}</title>
<style>
  body { margin:0; padding:0; background:#f4f4f0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; color:#1e293b; }
  .wrapper { max-width:580px; margin:32px auto; padding:0 16px 48px; }
  .card { background:#fffef9; border:1px solid #e5e3d8; border-radius:14px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.06); }
  .card-header { background:linear-gradient(135deg,#16a34a,#0f766e); padding:24px 28px 20px; }
  .card-header h1 { margin:0 0 4px; font-size:1.25rem; font-weight:800; color:#fff; }
  .card-header p  { margin:0; font-size:.82rem; color:rgba(255,255,255,.8); }
  .recipe-image { width:100%; max-height:220px; object-fit:cover; display:block; }
  .body-pad { padding:20px 28px; }
  .note-box { background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:12px 16px; margin-bottom:18px; font-size:.85rem; color:#15803d; }
  .note-box strong { display:block; margin-bottom:2px; font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; }
  .macro-row { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px; }
  .macro-chip { flex:1; min-width:80px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; text-align:center; }
  .macro-chip .val { font-size:1rem; font-weight:800; color:#0f172a; display:block; }
  .macro-chip .lbl { font-size:.65rem; font-weight:600; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; display:block; margin-top:2px; }
  .section-title { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; color:#94a3b8; margin:18px 0 8px; border-top:1px solid #f1ede4; padding-top:14px; }
  .ingredient-list { margin:0; padding:0 0 0 18px; font-size:.88rem; line-height:1.8; color:#374151; }
  .direction-item { display:flex; gap:10px; margin-bottom:10px; }
  .direction-num { width:24px; height:24px; border-radius:50%; background:#16a34a; color:#fff; font-size:.7rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
  .direction-text { font-size:.87rem; line-height:1.6; color:#374151; }
  .source-link { font-size:.78rem; color:#64748b; margin-top:14px; }
  .source-link a { color:#16a34a; }
  .footer { margin-top:28px; text-align:center; font-size:.75rem; color:#94a3b8; line-height:1.6; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    {{-- Header --}}
    <div class="card-header">
      <h1>🍽️ {{ $recipe->name }}</h1>
      <p>Recommended by your dietician — for {{ $patient->name }}</p>
    </div>

    {{-- Recipe image --}}
    @if($recipe->image_url)
      <img src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}" class="recipe-image">
    @endif

    <div class="body-pad">

      {{-- Dietician note --}}
      @if($note)
        <div class="note-box">
          <strong>Note from your dietician</strong>
          {{ $note }}
        </div>
      @endif

      {{-- Description --}}
      @if($recipe->description)
        <p style="font-size:.88rem;color:#475569;margin:0 0 16px;line-height:1.6">{{ $recipe->description }}</p>
      @endif

      {{-- Macros --}}
      @if($recipe->calories || $recipe->protein_g || $recipe->carbs_g || $recipe->fat_g)
        <div class="macro-row">
          @if($recipe->calories)
            <div class="macro-chip">
              <span class="val">{{ round($recipe->calories) }}</span>
              <span class="lbl">kcal</span>
            </div>
          @endif
          @if($recipe->protein_g)
            <div class="macro-chip">
              <span class="val">{{ round($recipe->protein_g) }}g</span>
              <span class="lbl">Protein</span>
            </div>
          @endif
          @if($recipe->carbs_g)
            <div class="macro-chip">
              <span class="val">{{ round($recipe->carbs_g) }}g</span>
              <span class="lbl">Carbs</span>
            </div>
          @endif
          @if($recipe->fat_g)
            <div class="macro-chip">
              <span class="val">{{ round($recipe->fat_g) }}g</span>
              <span class="lbl">Fat</span>
            </div>
          @endif
          @if($recipe->fiber_g)
            <div class="macro-chip">
              <span class="val">{{ round($recipe->fiber_g) }}g</span>
              <span class="lbl">Fiber</span>
            </div>
          @endif
        </div>
        @if($recipe->serving_size)
          <p style="font-size:.75rem;color:#94a3b8;margin:-10px 0 16px;">Per {{ $recipe->serving_size }}</p>
        @endif
      @endif

      {{-- Ingredients --}}
      @if(!empty($recipe->ingredients))
        <div class="section-title">Ingredients</div>
        <ul class="ingredient-list">
          @foreach($recipe->ingredients as $ing)
            <li>{{ $ing }}</li>
          @endforeach
        </ul>
      @endif

      {{-- Directions --}}
      @if($recipe->directions)
        <div class="section-title">Directions</div>
        @foreach(array_filter(explode("\n", $recipe->directions)) as $i => $step)
          <div class="direction-item">
            <div class="direction-num">{{ $i + 1 }}</div>
            <div class="direction-text">{{ $step }}</div>
          </div>
        @endforeach
      @endif

      {{-- Source link --}}
      @if($recipe->source_url)
        <p class="source-link">Full recipe: <a href="{{ $recipe->source_url }}" target="_blank">View on FatSecret</a></p>
      @endif

    </div>
  </div>

  <div class="footer">
    Sent via MindfulNutrico &nbsp;·&nbsp; Your dietician's digital practice<br>
    If you have questions, contact your dietician directly.
  </div>
</div>
</body>
</html>
