import React, { useEffect } from "react";
import { submitToApi } from "../utils/apiHandle.ts";


export default function Index() {

    const [vendedor, setVendedor] = React.useState("");
    const [vendedorData, setVendedorData] = React.useState("");

    async function handleSubmit(e) {
        e.preventDefault();
        const response = await submitToApi(e, vendedor);
        setVendedorData(response);
    }

    useEffect(() => {
        console.log("vendedor:", vendedorData);
    }, [vendedorData]);
    /* const content = vendedor?.result?.message?.content || artistData?.result?.aiCall?.message?.content;

    console.log("type of content:", typeof content); */

    return (
        <form onSubmit={handleSubmit}>
            <input type="text" className="border border-gray-300 p-2 mb-4" placeholder="Enter your artist name"
                    onChange={(e) => setVendedor(e.target.value)} value={vendedor}
                />
            <button type="submit" name="vendedores">Enviar Vendedores</button>
            <button type="submit" name="comisiones">Obtener Comisiones</button>
        </form>

    )
}