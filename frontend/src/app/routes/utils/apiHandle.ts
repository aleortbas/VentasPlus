export async function submitToApi(
    e: React.FormEvent<HTMLFormElement>,
    vendedorId: string,
): Promise<any> {
    e.preventDefault();

    // El botón que disparó el submit (vendedores o comisiones)
    let api = e.nativeEvent.submitter.name;

    // Definir endpoints disponibles
    const endpoints: Record<string, { method: string, url: string }> = {
        vendedores: { method: "POST", url: "http://localhost/backend/Public/api/vendedores.php" },
        comisiones: { method: "GET", url: "http://localhost/backend/Public/api/comisiones.php" }
    };

    const config = endpoints[api];
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

        // Solo POST lleva body
        if (config.method === "POST") {
            options.headers!["Content-Type"] = "application/json";
            options.body = JSON.stringify({
                vendedorId
            });
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
