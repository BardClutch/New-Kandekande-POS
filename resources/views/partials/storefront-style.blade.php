<style>
:root {
                /* ilustrasi: siang */
                --sky-a: #A9DAF2;
                --sky-b: #FFE6C7;
                --cloud: #FFFFFF;
                --b1: #E8C9B0;
                --b2: #C9D5E8;
                --b-win: #F6FBFF;
                --wall: #8FD3BC;
                --wall-dark: #6DB9A1;
                --trim: #2F5D50;
                --board: #2A1B12;
                --board-ink: #FFE8B8;
                --stripe-a: #E5492C;
                --stripe-b: #FFF4DE;
                --frame: #6D4527;
                --inside: #FFEDBE;
                --door: #8A4B2A;
                --walk: #D7D2C7;
                --road: #8D96A6;
                --road-line: #E9E6DD;
                --pot: #C46A3C;
                --leaf: #3F8F55;
                --lamp: #2D3440;
                --crust: #D79A4B;
                --crust-dark: #B67A33;
                --score: #F5DFA8;
}
.dark {
                --sky-a: #121A3E;
                --sky-b: #3C2D55;
                --cloud: #4A4F7A;
                --b1: #4A3B4F;
                --b2: #2F3858;
                --b-win: #FFD27A;
                --wall: #3F7F6D;
                --wall-dark: #2E6354;
                --trim: #1D3A32;
                --stripe-a: #C23A22;
                --stripe-b: #DCCDB0;
                --inside: #FFC95E;
                --door: #5A311C;
                --walk: #4B4C61;
                --road: #2D3248;
                --road-line: #6C7090;
                --pot: #8F4D2B;
                --leaf: #2C6B40;
}
            .scene text { font-family: var(--display); }
            /* ilustrasi: warna & peralihan siang-malam */
            .scene .fade { transition: fill .7s, stroke .7s, opacity .7s; }
            .s-b1 { fill: var(--b1); } .s-b2 { fill: var(--b2); } .s-bwin { fill: var(--b-win); }
            .s-wall { fill: var(--wall); } .s-walld { fill: var(--wall-dark); } .s-trim { fill: var(--trim); }
            .s-board { fill: var(--board); } .s-boardink { fill: var(--board-ink); }
            .s-a { fill: var(--stripe-a); } .s-b { fill: var(--stripe-b); }
            .s-frame { fill: var(--frame); } .s-inside { fill: var(--inside); } .s-door { fill: var(--door); }
            .s-walk { fill: var(--walk); } .s-road { fill: var(--road); } .s-rline { fill: var(--road-line); }
            .s-pot { fill: var(--pot); } .s-leaf { fill: var(--leaf); } .s-lamp { fill: var(--lamp); }
            .s-crust { fill: var(--crust); } .s-crustd { fill: var(--crust-dark); } .s-score { fill: var(--score); }
            .s-cloud { fill: var(--cloud); }
            .night-only { opacity: 0; transition: opacity .8s; }
            .dark .night-only { opacity: 1; }
            .day-only { opacity: 1; transition: opacity .8s; }
            .dark .day-only { opacity: 0; }
            .sun { transform: translateY(0); transition: transform 1.1s cubic-bezier(.6, 0, .3, 1); }
            .dark .sun { transform: translateY(520px); }
            .moon { transform: translateY(520px); transition: transform 1.1s cubic-bezier(.6, 0, .3, 1); }
            .dark .moon { transform: translateY(0); }
            .sky-night { opacity: 0; transition: opacity .9s; }
            .dark .sky-night { opacity: 1; }
            .glow { opacity: 0; transition: opacity .9s; }
            .dark .glow { opacity: 1; }
            /* animasi ringan (2D) */
            .cloud { animation: drift 70s linear infinite; }
            .cloud.c2 { animation-duration: 95s; animation-delay: -40s; }
            .cloud.c3 { animation-duration: 120s; animation-delay: -85s; }
            .puff { transform-box: fill-box; transform-origin: center; animation: puff 5.5s ease-out infinite; opacity: 0; }
            .puff.p2 { animation-delay: 1.8s; }
            .puff.p3 { animation-delay: 3.6s; }
            .hang { transform-box: fill-box; transform-origin: 50% 0; animation: swing 3.6s ease-in-out infinite alternate; }
            .plant { transform-box: fill-box; transform-origin: 50% 100%; animation: sway 4.5s ease-in-out infinite alternate; }
            .plant.q2 { animation-delay: -2s; }
            .bulb { animation: twinkle 2.4s ease-in-out infinite alternate; }
            .bulb:nth-child(2n) { animation-delay: -1.2s; }
            .bulb:nth-child(3n) { animation-delay: -.6s; }
            .star { animation: twinkle 3s ease-in-out infinite alternate; }
            .star:nth-child(2n) { animation-delay: -1.5s; }

            @keyframes drift { from { transform: translateX(-260px); } to { transform: translateX(1060px); } }
            @keyframes puff { 0% { opacity: 0; transform: translate(0, 0) scale(.5); } 15% { opacity: .9; } 100% { opacity: 0; transform: translate(36px, -120px) scale(2.2); } }
            @keyframes swing { from { transform: rotate(-8deg); } to { transform: rotate(8deg); } }
            @keyframes sway { from { transform: rotate(-2.5deg); } to { transform: rotate(2.5deg); } }
            @keyframes twinkle { from { opacity: .45; } to { opacity: 1; } }
</style>
