<svg class="scene" viewBox="-200 0 1200 1000" preserveAspectRatio="xMidYMax slice" xmlns="http://www.w3.org/2000/svg">
<defs>
    <linearGradient id="skyDay" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#A9DAF2"/><stop offset=".8" stop-color="#FFE6C7"/>
    </linearGradient>
    <linearGradient id="skyNight" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#101739"/><stop offset=".8" stop-color="#3C2D55"/>
    </linearGradient>
    <radialGradient id="warm" cx=".5" cy=".5" r=".5">
        <stop offset="0" stop-color="#FFC24D" stop-opacity=".55"/><stop offset="1" stop-color="#FFC24D" stop-opacity="0"/>
    </radialGradient>
</defs>

<!-- langit -->
<rect x="-200" width="1200" height="1000" fill="url(#skyDay)"/>
<rect class="sky-night" x="-200" width="1200" height="1000" fill="url(#skyNight)"/>
<g class="night-only" fill="#fff">
    <circle class="star" cx="-150" cy="120" r="1.8"/><circle class="star" cx="-60" cy="250" r="1.5"/><circle class="star" cx="-120" cy="380" r="1.4"/><circle class="star" cx="840" cy="70" r="2"/><circle class="star" cx="900" cy="220" r="1.6"/><circle class="star" cx="960" cy="340" r="1.4"/><circle class="star" cx="820" cy="400" r="1.5"/>
    <circle class="star" cx="90" cy="90" r="2.2"/><circle class="star" cx="210" cy="170" r="1.6"/>
    <circle class="star" cx="330" cy="60" r="2"/><circle class="star" cx="450" cy="150" r="1.6"/>
    <circle class="star" cx="540" cy="80" r="2.2"/><circle class="star" cx="720" cy="130" r="1.8"/>
    <circle class="star" cx="150" cy="260" r="1.5"/><circle class="star" cx="600" cy="270" r="1.6"/>
    <circle class="star" cx="760" cy="300" r="1.4"/><circle class="star" cx="60" cy="340" r="1.4"/>
</g>

<!-- matahari & bulan -->
<g class="sun">
    <circle cx="450" cy="352" r="48" fill="#FFE27A" opacity=".3"/>
    <circle cx="450" cy="352" r="30" fill="#FFE27A"/>
</g>
<g class="moon">
    <circle cx="450" cy="352" r="42" fill="#F3EEE7" opacity=".1"/>
    <circle cx="450" cy="352" r="26" fill="#F3EEE7"/>
    <circle cx="459" cy="344" r="5" fill="#D9D5C4"/><circle cx="442" cy="362" r="3.5" fill="#D9D5C4"/><circle cx="457" cy="364" r="2.5" fill="#D9D5C4"/>
</g>

<!-- awan -->
<g class="cloud" style="transform:translateX(-260px)"><g class="s-cloud fade" opacity=".9" transform="translate(0 70)"><rect x="0" y="260" width="150" height="34" rx="17"/><circle cx="48" cy="258" r="26"/><circle cx="92" cy="252" r="32"/></g></g>
<g class="cloud c2"><g class="s-cloud fade" opacity=".75" transform="translate(0 190)"><rect x="0" y="130" width="110" height="26" rx="13"/><circle cx="36" cy="128" r="20"/><circle cx="70" cy="123" r="24"/></g></g>
<g class="cloud c3"><g class="s-cloud fade" opacity=".8"><rect x="0" y="360" width="130" height="30" rx="15"/><circle cx="42" cy="358" r="23"/><circle cx="82" cy="352" r="28"/></g></g>

<!-- gedung belakang -->
<rect class="s-b2 fade" x="-200" y="540" width="150" height="290"/>
<rect class="s-b1 fade" x="840" y="500" width="170" height="330"/>
<g class="s-bwin fade"><rect x="-170" y="580" width="30" height="40" rx="3"/><rect x="-110" y="580" width="30" height="40" rx="3"/><rect x="-170" y="660" width="30" height="40" rx="3"/><rect x="-110" y="660" width="30" height="40" rx="3"/><rect x="870" y="540" width="30" height="40" rx="3"/><rect x="930" y="540" width="30" height="40" rx="3"/><rect x="870" y="620" width="30" height="40" rx="3"/><rect x="930" y="620" width="30" height="40" rx="3"/></g>
<rect class="s-b1 fade" x="-30" y="470" width="190" height="360"/>
<rect class="s-b2 fade" x="650" y="420" width="190" height="410"/>
<g class="s-bwin fade">
    <rect x="14" y="510" width="34" height="46" rx="3"/><rect x="74" y="510" width="34" height="46" rx="3"/>
    <rect x="14" y="600" width="34" height="46" rx="3"/><rect x="74" y="600" width="34" height="46" rx="3"/>
    <rect x="690" y="470" width="34" height="46" rx="3"/><rect x="750" y="470" width="34" height="46" rx="3"/>
    <rect x="690" y="560" width="34" height="46" rx="3"/><rect x="750" y="560" width="34" height="46" rx="3"/>
</g>

<!-- cerobong + asap -->
<rect class="s-trim fade" x="540" y="372" width="46" height="84"/>
<rect class="s-trim fade" x="534" y="366" width="58" height="12" rx="3"/>
<g fill="#F7F3EA">
    <circle class="puff" cx="563" cy="352" r="14"/>
    <circle class="puff p2" cx="563" cy="352" r="14"/>
    <circle class="puff p3" cx="563" cy="352" r="14"/>
</g>

<!-- bangunan toko -->
<rect class="s-wall fade" x="130" y="470" width="540" height="350"/>
<rect class="s-walld fade" x="130" y="790" width="540" height="30"/>
<rect class="s-trim fade" x="116" y="440" width="568" height="38" rx="6"/>
<rect class="s-walld fade" x="116" y="478" width="568" height="8" opacity=".6"/>

<!-- lampu hias -->
@php
    $bulbs = '';
    for ($i = 0; $i <= 10; $i++) {
        $t = $i / 10;
        $x = (1 - $t) ** 2 * 140 + 2 * (1 - $t) * $t * 400 + $t ** 2 * 660;
        $y = (1 - $t) ** 2 * 494 + 2 * (1 - $t) * $t * 566 + $t ** 2 * 494;
        $bulbs .= '<circle class="bulb" cx="' . round($x) . '" cy="' . round($y + 5) . '" r="5"/>';
    }
@endphp
<path d="M140 494 Q400 566 660 494" fill="none" stroke="#2A1B12" stroke-width="1.6" opacity=".6"/>
<g class="night-only" fill="#FFD36B">{!! $bulbs !!}</g>
<g class="day-only" fill="#F2D9A0" opacity=".9">{!! preg_replace('/ class="bulb"/', '', $bulbs) !!}</g>

<!-- papan nama -->
<rect class="s-board fade" x="220" y="540" width="360" height="86" rx="12"/>
<rect x="228" y="548" width="344" height="70" rx="8" fill="none" stroke="#FFE8B8" stroke-opacity=".35" stroke-width="1.5"/>
<text class="s-boardink fade" x="400" y="591" text-anchor="middle" font-size="46" font-weight="800" letter-spacing="-1">Kandekande</text>
<text class="s-boardink fade" x="400" y="611" text-anchor="middle" font-size="14" font-weight="600" opacity=".8" letter-spacing="2">ROTI &amp; KUE</text>

<!-- kanopi -->
<g>
    @foreach (range(0, 9) as $i)
        <rect class="{{ $i % 2 ? 's-b' : 's-a' }} fade" x="{{ 150 + $i * 50 }}" y="644" width="50" height="46"/>
        <circle class="{{ $i % 2 ? 's-b' : 's-a' }} fade" cx="{{ 175 + $i * 50 }}" cy="690" r="25"/>
    @endforeach
</g>

<!-- etalase -->
<rect class="s-frame fade" x="176" y="722" width="250" height="96" rx="8"/>
<rect class="s-inside fade" x="186" y="732" width="230" height="76" rx="4"/>
<ellipse class="glow" cx="300" cy="770" rx="210" ry="110" fill="url(#warm)"/>
<rect class="s-frame fade" x="186" y="768" width="230" height="5"/>
<!-- roti rak atas -->
<path class="s-crust fade" d="M200 768c0-16 10-22 22-22s22 6 22 22z"/>
<path class="s-crust fade" d="M258 768c0-16 10-22 22-22s22 6 22 22z"/>
<path class="s-crust fade" d="M322 768c2-12 8-18 17-18s15 6 17 18c-7-4-12-5-17-5s-10 1-17 5z"/>
<path class="s-score fade" d="M210 756l6-6M222 756l6-6M268 756l6-6M280 756l6-6" stroke="none"/>
<rect class="s-score fade" x="208" y="753" width="12" height="3.5" rx="2" transform="rotate(-30 214 755)"/>
<rect class="s-score fade" x="224" y="753" width="12" height="3.5" rx="2" transform="rotate(-30 230 755)"/>
<rect class="s-score fade" x="266" y="753" width="12" height="3.5" rx="2" transform="rotate(-30 272 755)"/>
<rect class="s-score fade" x="282" y="753" width="12" height="3.5" rx="2" transform="rotate(-30 288 755)"/>
<!-- rak bawah -->
<rect class="s-crustd fade" x="200" y="786" width="100" height="14" rx="7" transform="rotate(-6 250 793)"/>
<rect class="s-crust fade" x="204" y="796" width="100" height="10" rx="5" transform="rotate(-3 254 801)"/>
<rect x="330" y="782" width="66" height="26" rx="3" fill="#FFF4DE"/>
<rect x="330" y="782" width="66" height="10" rx="3" fill="#8B4A2B"/>
<circle cx="363" cy="778" r="5" fill="#E5492C"/>
<path d="M190 736l60 0 -42 72h-18z" fill="#fff" opacity=".18"/>

<!-- pintu -->
<rect class="s-frame fade" x="462" y="704" width="136" height="116" rx="0"/>
<rect class="s-door fade" x="470" y="712" width="120" height="108" rx="10"/>
<rect class="s-inside fade" x="482" y="722" width="96" height="52" rx="6"/>
<circle cx="486" cy="790" r="5" fill="#FFC93C"/>
<g class="hang">
    <line x1="530" y1="728" x2="530" y2="744" stroke="#2A1B12" stroke-width="2"/>
    <rect x="506" y="744" width="48" height="26" rx="5" fill="#FFF4DE"/>
    <text x="530" y="762" text-anchor="middle" font-size="13" font-weight="800" fill="#D4501E">BUKA</text>
</g>

<!-- trotoar & jalan -->
<rect class="s-walk fade" x="-200" y="820" width="1200" height="62"/>
<rect class="s-road fade" x="-200" y="882" width="1200" height="118"/>
<g class="s-rline fade"><rect x="-130" y="936" width="90" height="8" rx="4"/><rect x="30" y="936" width="90" height="8" rx="4"/><rect x="190" y="936" width="90" height="8" rx="4"/><rect x="350" y="936" width="90" height="8" rx="4"/><rect x="510" y="936" width="90" height="8" rx="4"/><rect x="670" y="936" width="90" height="8" rx="4"/><rect x="830" y="936" width="90" height="8" rx="4"/></g>

<!-- pohon -->
<g class="plant"><rect class="s-lamp fade" x="-84" y="700" width="12" height="124" rx="4"/><circle class="s-leaf fade" cx="-78" cy="680" r="54"/><circle class="s-leaf fade" cx="-118" cy="712" r="36"/><circle class="s-leaf fade" cx="-40" cy="712" r="38"/></g>
<g class="plant q2"><rect class="s-lamp fade" x="784" y="690" width="12" height="134" rx="4"/><circle class="s-leaf fade" cx="790" cy="668" r="58"/><circle class="s-leaf fade" cx="748" cy="704" r="38"/><circle class="s-leaf fade" cx="832" cy="704" r="40"/></g>

<!-- pot tanaman -->
<g class="plant"><path class="s-leaf fade" d="M436 790c-14-22-12-46 4-62 4 20 8 34 0 62z"/><path class="s-leaf fade" d="M436 790c14-18 30-30 46-30-8 18-24 28-46 30z"/></g>
<path class="s-pot fade" d="M418 790h36l-6 34h-24z"/>
<g class="plant q2"><path class="s-leaf fade" d="M628 790c-14-22-12-46 4-62 4 20 8 34 0 62z"/><path class="s-leaf fade" d="M628 790c14-18 30-30 46-30-8 18-24 28-46 30z"/></g>
<path class="s-pot fade" d="M610 790h36l-6 34h-24z"/>

<!-- lampu jalan -->
<ellipse class="glow" cx="80" cy="640" rx="110" ry="190" fill="url(#warm)"/>
<rect class="s-lamp fade" x="76" y="600" width="8" height="226" rx="3"/>
<rect class="s-lamp fade" x="62" y="818" width="36" height="8" rx="3"/>
<path class="s-lamp fade" d="M58 604h44l-8-22H66z"/>
<circle class="fade" cx="80" cy="610" r="7" fill="#FFE9A8"/>
                    </svg>
