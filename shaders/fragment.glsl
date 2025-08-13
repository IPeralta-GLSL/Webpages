precision mediump float;

uniform float time;
uniform vec2 resolution;

float noise(vec2 p) {
    return fract(sin(dot(p, vec2(12.9898, 78.233))) * 43758.5453);
}

float smoothNoise(vec2 p) {
    vec2 i = floor(p);
    vec2 f = fract(p);
    f = f * f * (3.0 - 2.0 * f);
    
    float a = noise(i);
    float b = noise(i + vec2(1.0, 0.0));
    float c = noise(i + vec2(0.0, 1.0));
    float d = noise(i + vec2(1.0, 1.0));
    
    return mix(mix(a, b, f.x), mix(c, d, f.x), f.y);
}

float fractalNoise(vec2 p) {
    float value = 0.0;
    float amplitude = 0.5;
    for(int i = 0; i < 6; i++) {
        value += amplitude * smoothNoise(p);
        p *= 2.0;
        amplitude *= 0.5;
    }
    return value;
}

vec3 cosmicBackground(vec2 uv) {
    // Base dark space color
    vec3 color = vec3(0.02, 0.02, 0.08);
    
    // Nebula layers
    float nebula1 = fractalNoise(uv * 2.0 + time * 0.01);
    float nebula2 = fractalNoise(uv * 1.5 + time * 0.015);
    
    // Purple/blue nebula
    vec3 nebulaColor1 = vec3(0.2, 0.1, 0.4) * nebula1 * 0.6;
    vec3 nebulaColor2 = vec3(0.1, 0.2, 0.5) * nebula2 * 0.4;
    
    color += nebulaColor1 + nebulaColor2;
    
    // Stars
    float starField = noise(uv * 200.0);
    float starIntensity = smoothstep(0.98, 1.0, starField);
    
    // Twinkling effect
    float twinkle = sin(time * 3.0 + noise(uv * 100.0) * 6.28) * 0.5 + 0.5;
    starIntensity *= twinkle;
    
    // Different star colors
    vec3 starColor = mix(vec3(1.0), vec3(0.8, 0.9, 1.0), noise(uv * 50.0));
    color += starColor * starIntensity * 0.8;
    
    // Large bright stars
    float bigStars = noise(uv * 50.0);
    float bigStarIntensity = smoothstep(0.995, 1.0, bigStars);
    float bigTwinkle = sin(time * 2.0 + noise(uv * 25.0) * 6.28) * 0.5 + 0.5;
    color += vec3(1.0) * bigStarIntensity * bigTwinkle * 1.2;
    
    return color;
}

void main() {
    vec2 uv = gl_FragCoord.xy / resolution.xy;
    
    vec3 color = cosmicBackground(uv);
    
    // Vignette effect
    float dist = distance(uv, vec2(0.5));
    float vignette = 1.0 - smoothstep(0.3, 1.0, dist);
    color *= vignette * 0.8 + 0.2;
    
    gl_FragColor = vec4(color, 1.0);
}
