attribute vec2 position;

uniform float time;
uniform vec2 resolution;
uniform vec2 pan;
uniform float zoom;

varying vec2 vUv;

void main() {
    vec2 uv = position * 0.5 * vec2(resolution.x / resolution.y, 1.0);
    float sway = sin(time * 0.05) * 0.012;
    vUv = uv / zoom + pan + vec2(sway, sway * 0.55);
    gl_Position = vec4(position, 0.0, 1.0);
}
