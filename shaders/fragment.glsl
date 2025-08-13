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
    vec3 color = vec3(0.02, 0.04, 0.08);
    
    float nebula1 = fractalNoise(uv * 1.5 + time * 0.008);
    float nebula2 = fractalNoise(uv * 2.5 + time * 0.012);
    float nebula3 = fractalNoise(uv * 0.8 + time * 0.005);
    
    vec3 nebulaColor1 = vec3(0.1, 0.4, 0.8) * nebula1 * 0.4;
    vec3 nebulaColor2 = vec3(0.0, 0.6, 1.0) * nebula2 * 0.3;
    vec3 nebulaColor3 = vec3(0.2, 0.5, 0.9) * nebula3 * 0.2;
    
    color += nebulaColor1 + nebulaColor2 + nebulaColor3;
    
    float flow1 = sin(uv.x * 10.0 + time * 2.0) * cos(uv.y * 8.0 + time * 1.5);
    float flow2 = sin(uv.x * 15.0 - time * 1.8) * cos(uv.y * 12.0 - time * 2.2);
    vec3 flowColor = vec3(0.0, 0.8, 1.0) * (flow1 + flow2) * 0.05 * smoothstep(0.3, 0.7, nebula1);
    color += flowColor;
    
    float starField = noise(uv * 80.0); // Reduced from 120 to make even fewer stars
    
    float twinkle = sin(time * 4.0 + noise(uv * 50.0) * 6.28) * 0.5 + 0.5;
    starIntensity *= twinkle;
    
    vec3 starColor = mix(vec3(0.8, 1.0, 1.0), vec3(0.4, 0.8, 1.0), noise(uv * 40.0));
    color += starColor * starIntensity * 2.0; // Increased brightness even more
    
    float bigStars = noise(uv * 15.0); // Reduced from 25 to make much bigger stars
    float bigTwinkle = sin(time * 1.5 + noise(uv * 10.0) * 6.28) * 0.5 + 0.5;
    vec3 bigStarColor = vec3(0.5, 0.9, 1.0);
    color += bigStarColor * bigStarIntensity * bigTwinkle * 3.0; // Increased brightness
    
    float glow = fractalNoise(uv * 0.5 + time * 0.003);
    color += vec3(0.1, 0.3, 0.6) * glow * 0.1;
    
    return color;
}

void main() {
    vec2 uv = gl_FragCoord.xy / resolution.xy;
    
    vec3 color = cosmicBackground(uv);
    
    float dist = distance(uv, vec2(0.5));
    float vignette = 1.0 - smoothstep(0.5, 1.2, dist);
    color *= vignette * 0.8 + 0.1; // Reduced from 0.9 to 0.8 for darker effect
    
    color = pow(color, vec3(0.95)); // Less gamma correction
    color *= 0.9; // Overall darker multiplier (was 1.1)
    
    gl_FragColor = vec4(color, 1.0);
}
