precision mediump float;

uniform float time;
uniform vec2 resolution;

mat3 rotateY(float angle) {
    float c = cos(angle);
    float s = sin(angle);
    return mat3(
        c, 0.0, s,
        0.0, 1.0, 0.0,
        -s, 0.0, c
    );
}

mat3 rotateX(float angle) {
    float c = cos(angle);
    float s = sin(angle);
    return mat3(
        1.0, 0.0, 0.0,
        0.0, c, -s,
        0.0, s, c
    );
}

float sdSphere(vec3 p, float r) {
    return length(p) - r;
}

float sdBox(vec3 p, vec3 b) {
    vec3 q = abs(p) - b;
    return length(max(q, 0.0)) + min(max(q.x, max(q.y, q.z)), 0.0);
}

float sdTorus(vec3 p, vec2 t) {
    vec2 q = vec2(length(p.xz) - t.x, p.y);
    return length(q) - t.y;
}

float smin(float a, float b, float k) {
    float h = max(k - abs(a - b), 0.0) / k;
    return min(a, b) - h * h * k * (1.0 / 4.0);
}

float scene(vec3 p) {
    vec3 pos1 = p + vec3(sin(time * 0.7) * 2.0, cos(time * 0.5) * 1.5, 0.0);
    float sphere1 = sdSphere(pos1, 1.2);
    
    vec3 pos2 = p + vec3(-sin(time * 0.8) * 2.5, sin(time * 0.6) * 1.8, cos(time * 0.4) * 1.0);
    float sphere2 = sdSphere(pos2, 0.8);
    
    vec3 pos3 = p;
    pos3 = rotateY(time * 0.5) * pos3;
    float torus = sdTorus(pos3, vec2(3.0, 0.3));
    
    vec3 pos4 = p + vec3(0.0, sin(time * 0.9) * 0.5, 0.0);
    pos4 = rotateX(time * 0.7) * rotateY(time * 0.3) * pos4;
    float box = sdBox(pos4, vec3(0.6, 0.6, 0.6));
    
    float merged = smin(sphere1, sphere2, 0.8);
    merged = smin(merged, box, 0.5);
    return min(merged, torus);
}

vec3 getNormal(vec3 p) {
    float eps = 0.001;
    return normalize(vec3(
        scene(p + vec3(eps, 0.0, 0.0)) - scene(p - vec3(eps, 0.0, 0.0)),
        scene(p + vec3(0.0, eps, 0.0)) - scene(p - vec3(0.0, eps, 0.0)),
        scene(p + vec3(0.0, 0.0, eps)) - scene(p - vec3(0.0, 0.0, eps))
    ));
}

float raymarch(vec3 ro, vec3 rd) {
    float t = 0.0;
    for(int i = 0; i < 80; i++) {
        vec3 p = ro + t * rd;
        float d = scene(p);
        if(d < 0.001 || t > 20.0) break;
        t += d;
    }
    return t;
}

vec3 lighting(vec3 pos, vec3 normal, vec3 rd) {
    vec3 lightPos = vec3(5.0, 5.0, 5.0);
    vec3 lightDir = normalize(lightPos - pos);
    
    float diff = max(dot(normal, lightDir), 0.0);
    
    vec3 viewDir = normalize(-rd);
    vec3 reflectDir = reflect(-lightDir, normal);
    float spec = pow(max(dot(viewDir, reflectDir), 0.0), 32.0);
    
    vec3 ambient = vec3(0.1, 0.2, 0.4);
    vec3 diffuse = vec3(0.3, 0.6, 1.0) * diff;
    vec3 specular = vec3(1.0, 0.8, 0.6) * spec * 0.5;
    
    float fresnel = pow(1.0 - max(dot(normal, viewDir), 0.0), 2.0);
    vec3 fresnelColor = vec3(0.8, 0.9, 1.0) * fresnel * 0.3;
    
    return ambient + diffuse + specular + fresnelColor;
}

vec3 background(vec3 rd) {
    float stars = smoothstep(0.98, 1.0, sin(rd.x * 100.0) * cos(rd.y * 100.0) * sin(rd.z * 100.0));
    vec3 starColor = vec3(0.8, 0.9, 1.0) * stars;
    
    vec3 nebula = vec3(0.05, 0.1, 0.2) + vec3(0.1, 0.2, 0.4) * (sin(rd.x * 2.0 + time * 0.1) * 0.5 + 0.5);
    
    return starColor + nebula;
}

void main() {
    vec2 uv = (gl_FragCoord.xy - 0.5 * resolution.xy) / resolution.y;
    
    vec3 ro = vec3(0.0, 0.0, 8.0);
    ro = rotateY(time * 0.2) * rotateX(sin(time * 0.15) * 0.3) * ro;
    
    vec3 target = vec3(0.0, 0.0, 0.0);
    vec3 forward = normalize(target - ro);
    vec3 right = normalize(cross(forward, vec3(0.0, 1.0, 0.0)));
    vec3 up = cross(right, forward);
    
    vec3 rd = normalize(forward + uv.x * right + uv.y * up);
    
    float t = raymarch(ro, rd);
    vec3 color;
    
    if(t < 20.0) {
        vec3 pos = ro + t * rd;
        vec3 normal = getNormal(pos);
        color = lighting(pos, normal, rd);
        
        float glow = 1.0 / (1.0 + t * t * 0.1);
        color += vec3(0.2, 0.4, 0.8) * glow * 0.3;
    } else {
        color = background(rd);
    }
    
    color = pow(color, vec3(0.8));
    
    gl_FragColor = vec4(color, 1.0);
}
