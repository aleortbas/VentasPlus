export async function submitToApi(
    e: React.FormEvent<HTMLFormElement> | null,
    vendedorId: string,
    nombre: string,
    apiName?: string // indicar la API si no viene de form
): Promise<any> {
    if (e) e.preventDefault();

    console.log("NOMBRE: ", nombre);
    console.log("VENDEDOR ID: ", vendedorId);

    // Determinar qué API usar
    const api = e ? e.nativeEvent.submitter.name : apiName;

    const endpoints: Record<string, { method: string, url: string }> = {
        vendedores: { method: "POST", url: "http://localhost:8000/Public/api/vendedores.php" }
    };

    const config = endpoints[api!];
    if (!config) {
        console.error("API no reconocida:", api);
        return;
    }

    try {
        const options: RequestInit = {
            method: config.method,
            credentials: "include",
            mode: "cors",
            headers: {}
        };

        if (config.method === "POST") {
            options.headers!["Content-Type"] = "application/json";
            options.body = JSON.stringify({ vendedorId, nombre });
        }

        console.log("Realizando solicitud a la API:", config.url, options);

        const res = await fetch(config.url, options);
        const data = await res.json();

        console.log("Respuesta de la API:", data);
        return data;
    } catch (error) {
        console.error("Error al hacer la solicitud:", error);
    }
}
