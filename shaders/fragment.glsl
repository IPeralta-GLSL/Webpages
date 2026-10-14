#ifdef GL_FRAGMENT_PRECISION_HIGH
precision highp float;
#else
precision mediump float;
#endif

uniform float time;
uniform vec2 resolution;

float hash21(vec2 p) {
    p = fract(p * vec2(234.34, 435.345));
    p += dot(p, p + 34.23);
    return fract(p.x * p.y);
}

float noise(vec2 p) {
    vec2 i = floor(p);
    vec2 f = fract(p);
    vec2 u = f * f * (3.0 - 2.0 * f);
    float a = hash21(i);
    float b = hash21(i + vec2(1.0, 0.0));
    float c = hash21(i + vec2(0.0, 1.0));
    float d = hash21(i + vec2(1.0, 1.0));
    return mix(mix(a, b, u.x), mix(c, d, u.x), u.y);
}

float fbm(vec2 p) {
    float value = 0.0;
    float amplitude = 0.5;
    mat2 rot = mat2(0.8, 0.6, -0.6, 0.8);
    for (int i = 0; i < 5; i++) {
        value += amplitude * noise(p);
        p = rot * p * 2.03;
        amplitude *= 0.5;
    }
    return value;
}

vec3 starField(vec2 uv, float scale, float density, float twinkleSpeed) {
    vec3 col = vec3(0.0);
    vec2 grid = uv * scale;
    vec2 id = floor(grid);
    vec2 cell = fract(grid) - 0.5;
    float cellSize = 1.0 / scale;

    for (int y = -1; y <= 1; y++) {
        for (int x = -1; x <= 1; x++) {
            vec2 offset = vec2(float(x), float(y));
            vec2 sid = id + offset;
            float rnd = hash21(sid);
            if (rnd > density) continue;

            vec2 pos = cell - offset + (vec2(hash21(sid + 11.0), hash21(sid + 27.0)) - 0.5) * 0.8;
            float dist = length(pos) * cellSize;

            float size = clamp(mix(0.15, 0.55, hash21(sid + 5.0)) * cellSize, 0.003, 0.009);
            float fade = 1.0 - smoothstep(0.03, 0.045, dist);
            float core = (1.0 - smoothstep(size * 0.3, size, dist)) * fade;
            float halo = exp(-dist / max(size * 1.1, 0.003)) * size * 7.0 * fade;

            float twinkle = 0.75 + 0.25 * sin(time * twinkleSpeed + rnd * 40.0);
            vec3 tint = mix(vec3(0.62, 0.76, 1.0), vec3(1.0, 0.84, 0.62), hash21(sid + 9.0));
            col += tint * (core + halo) * twinkle * mix(0.7, 1.6, hash21(sid + 3.0));
        }
    }
    return col;
}

void main() {
    vec2 uv = (gl_FragCoord.xy - 0.5 * resolution.xy) / resolution.y;

    float spin = time * 0.015;
    mat2 rot = mat2(cos(spin), -sin(spin), sin(spin), cos(spin));

    vec2 drift = vec2(time * 0.03, time * 0.01);
    vec2 p = rot * uv + drift;

    vec2 warp = vec2(fbm(p * 1.5 + time * 0.035), fbm(p * 1.5 + 4.7));
    float nebulaA = fbm(p * 1.8 + warp * 1.2 + vec2(time * 0.025, 0.0));
    float nebulaB = fbm(p * 3.1 - warp * 0.8 + vec2(0.0, time * 0.02));

    float band = exp(-pow(dot(p, vec2(0.28, 1.0)) * 1.9, 2.0));

    vec3 color = vec3(0.004, 0.008, 0.022);

    vec3 nebulaViolet = mix(vec3(0.03, 0.008, 0.09), vec3(0.34, 0.09, 0.42), smoothstep(0.35, 0.95, nebulaA));
    color += nebulaViolet * (0.5 + 0.5 * band) * 0.55;

    vec3 nebulaBlue = mix(vec3(0.008, 0.025, 0.08), vec3(0.05, 0.16, 0.34), smoothstep(0.4, 0.98, nebulaB));
    color += nebulaBlue * 0.5;

    vec3 nebulaWarm = mix(vec3(0.12, 0.03, 0.15), vec3(0.42, 0.12, 0.3), smoothstep(0.35, 0.92, nebulaB));
    color += nebulaWarm * smoothstep(0.3, 0.8, nebulaA * nebulaB) * 0.45;

    float dust = fbm(p * 2.2 + 13.0);
    color *= mix(1.0, 0.07, smoothstep(0.38, 0.72, dust));

    color += vec3(0.45, 0.4, 0.32) * band * band * 0.12;
    color += vec3(0.35, 0.45, 0.7) * band * 0.05;

    vec2 starUv = p + vec2(time * 0.003, 0.0);
    vec3 stars = vec3(0.0);
    stars += starField(starUv, 28.0, 0.10, 2.2) * 0.9;
    stars += starField(starUv + vec2(time * 0.006, -time * 0.002) + 3.7, 50.0, 0.05, 1.6) * 0.55;
    stars += starField(starUv + vec2(time * 0.004, time * 0.0015) + 9.1, 60.0, 0.035, 1.1) * 0.4;
    stars += starField(starUv - vec2(time * 0.002, 0.0), 10.0, 0.04, 0.7) * vec3(1.0, 0.95, 0.9) * 1.2;

    color += stars / (1.0 + stars * 0.35);

    float vignette = 1.0 - 0.4 * dot(uv, uv);
    color *= vignette;

    color = pow(color, vec3(1.08));

    gl_FragColor = vec4(color, 1.0);
}
