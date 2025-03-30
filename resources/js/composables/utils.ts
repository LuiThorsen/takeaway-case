const hexToRgb = (hex: `#${string}`) => {
    let alpha = false,
        h = hex.slice(hex.startsWith('#') ? 1 : 0);
    if (h.length === 3) h = [...h].map((x) => x + x).join('');
    else if (h.length === 8) alpha = true;
    const hp = parseInt(h, 16);
    return (
        'rgb' +
        (alpha ? 'a' : '') +
        '(' +
        (hp >>> (alpha ? 24 : 16)) +
        ', ' +
        ((hp & (alpha ? 0x00ff0000 : 0x00ff00)) >>> (alpha ? 16 : 8)) +
        ', ' +
        ((hp & (alpha ? 0x0000ff00 : 0x0000ff)) >>> (alpha ? 8 : 0)) +
        (alpha ? `, ${hp & 0x000000ff}` : '') +
        ')'
    );
};

const rgbToHsl = (r: number, g: number, b: number) => {
    r /= 255;
    g /= 255;
    b /= 255;
    const l = Math.max(r, g, b);
    const s = l - Math.min(r, g, b);
    const h = s ? (l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s) : 0;
    return [60 * h < 0 ? 60 * h + 360 : 60 * h, 100 * (s ? (l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s))) : 0), (100 * (2 * l - s)) / 2];
};

export function lightenColor(hex: `#${string}`): string {
    if (!hex) return '';

    const rgb = hexToRgb(hex)
        .replace(/rgb\(|\)/g, '')
        .split(', ');
    const hsl = rgbToHsl(+rgb[0], +rgb[1], +rgb[2]);

    return `hsl(${hsl[0]} ${hsl[1]}% ${hsl[2] < 45 ? hsl[2] + 50 : 95}%)`;
}
