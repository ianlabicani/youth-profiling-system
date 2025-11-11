<svg {{ $attributes }} viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Outer Circle (Dark Blue Background) -->
  <circle cx="100" cy="100" r="100" fill="#1a3a52"/>

  <!-- Inner White Circle Border -->
  <circle cx="100" cy="100" r="95" fill="none" stroke="white" stroke-width="2"/>

  <!-- Center Circle for Main Content -->
  <circle cx="100" cy="100" r="85" fill="none" stroke="white" stroke-width="1.5" opacity="0.8"/>

  <!-- Bell Tower (Center) -->
  <g transform="translate(100, 65)">
    <!-- Tower Base -->
    <rect x="-8" y="0" width="16" height="20" fill="#d4af37" stroke="#8b7620" stroke-width="0.5"/>

    <!-- Tower Walls -->
    <line x1="-8" y1="5" x2="8" y2="5" stroke="#8b7620" stroke-width="0.5" opacity="0.6"/>
    <line x1="-8" y1="10" x2="8" y2="10" stroke="#8b7620" stroke-width="0.5" opacity="0.6"/>
    <line x1="-8" y1="15" x2="8" y2="15" stroke="#8b7620" stroke-width="0.5" opacity="0.6"/>

    <!-- Roof (Cone) -->
    <polygon points="0,-5 -10,0 10,0" fill="#d4af37" stroke="#8b7620" stroke-width="0.5"/>
    <line x1="-10" y1="0" x2="10" y2="0" stroke="#8b7620" stroke-width="1" opacity="0.4"/>

    <!-- Bell -->
    <ellipse cx="0" cy="8" rx="6" ry="5" fill="#c9a227" stroke="#8b7620" stroke-width="0.5"/>
    <line x1="-2" y1="7" x2="2" y2="7" stroke="#8b7620" stroke-width="0.5" opacity="0.6"/>

    <!-- Clapper -->
    <line x1="0" y1="8" x2="0" y2="12" stroke="#8b7620" stroke-width="1"/>
    <circle cx="0" cy="13" r="1" fill="#8b7620"/>
  </g>

  <!-- Church (Right side of tower) -->
  <g transform="translate(125, 75)">
    <!-- Church Base -->
    <rect x="-6" y="5" width="12" height="15" fill="#d4af37" stroke="#8b7620" stroke-width="0.5"/>

    <!-- Steeple -->
    <polygon points="0,-8 -8,5 8,5" fill="#d4af37" stroke="#8b7620" stroke-width="0.5"/>

    <!-- Cross on Steeple -->
    <line x1="0" y1="-5" x2="0" y2="2" stroke="#8b7620" stroke-width="0.8"/>
    <line x1="-1.5" y1="-1" x2="1.5" y2="-1" stroke="#8b7620" stroke-width="0.8"/>

    <!-- Church Door -->
    <rect x="-2" y="12" width="4" height="6" fill="#8b7620" stroke="#8b7620" stroke-width="0.3"/>

    <!-- Windows -->
    <rect x="-4" y="8" width="2" height="2" fill="#c9a227" stroke="#8b7620" stroke-width="0.3"/>
    <rect x="2" y="8" width="2" height="2" fill="#c9a227" stroke="#8b7620" stroke-width="0.3"/>
  </g>

  <!-- Decorative Fields -->
  <g transform="translate(100, 130)">
    <ellipse cx="0" cy="0" rx="25" ry="8" fill="none" stroke="#d4af37" stroke-width="0.8" opacity="0.7"/>
    <!-- Field lines -->
    <line x1="-20" y1="-1" x2="20" y2="-1" stroke="#d4af37" stroke-width="0.5" opacity="0.5"/>
    <line x1="-20" y1="1" x2="20" y2="1" stroke="#d4af37" stroke-width="0.5" opacity="0.5"/>
    <line x1="-20" y1="3" x2="20" y2="3" stroke="#d4af37" stroke-width="0.5" opacity="0.5"/>
  </g>

  <!-- Left Laurel Wreath -->
  <g transform="translate(60, 100)">
    <path d="M 0 20 Q -8 15 -10 5 Q -8 0 0 -5" fill="none" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round"/>
    <!-- Leaves on left wreath -->
    <circle cx="-2" cy="18" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="-6" cy="14" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="-9" cy="9" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="-10" cy="3" r="1.5" fill="#d4af37" opacity="0.8"/>
  </g>

  <!-- Right Laurel Wreath -->
  <g transform="translate(140, 100)">
    <path d="M 0 20 Q 8 15 10 5 Q 8 0 0 -5" fill="none" stroke="#d4af37" stroke-width="1.5" stroke-linecap="round"/>
    <!-- Leaves on right wreath -->
    <circle cx="2" cy="18" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="6" cy="14" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="9" cy="9" r="1.5" fill="#d4af37" opacity="0.8"/>
    <circle cx="10" cy="3" r="1.5" fill="#d4af37" opacity="0.8"/>
  </g>

  <!-- Text Arc - Top -->
  <defs>
    <path id="topArc" d="M 35,100 A 65,65 0 0,1 165,100" fill="none"/>
  </defs>
  <text font-family="Arial, sans-serif" font-size="14" font-weight="bold" fill="white" letter-spacing="2">
    <textPath href="#topArc" startOffset="50%" text-anchor="middle">
      YOUTH DIGITAL PROFILING SYSTEM
    </textPath>
  </text>

  <!-- Text Arc - Bottom -->
  <defs>
    <path id="bottomArc" d="M 165,100 A 65,65 0 0,1 35,100" fill="none"/>
  </defs>
  <text font-family="Arial, sans-serif" font-size="12" font-weight="bold" fill="white" letter-spacing="1.5">
    <textPath href="#bottomArc" startOffset="50%" text-anchor="middle">
      CAMALANIUGAN • CAGAYAN
    </textPath>
  </text>
</svg>
