import React, { useEffect } from "react";
import { useNavigate } from 'react-router-dom';

import { submitToApi } from "../../routes/utils/apiHandle.ts";

export default function Comisiones() {

    const [vendedor, setVendedor] = React.useState("");
    const [dataVendedor, setDataVendedor] = React.useState([]);

     async function handleSubmit(e) {
        e.preventDefault();
        const response = await submitToApi(e, "", vendedor);
        setDataVendedor(response);
    }

    useEffect(() => {
        console.log("dataVendedor:", dataVendedor);
    }, [dataVendedor]);
    const content = dataVendedor;

    console.log("type of content:", typeof content);


    return (
        <div>
            <h1>comisiones</h1>
            <form onSubmit={handleSubmit}>
                <h1 className="text-2xl font-bold mb-4">INPUT</h1>
                <input type="text" className="border border-gray-300 p-2 mb-4" placeholder="Enter your artist name"
                    onChange={(e) => setVendedor(e.target.value)} value={vendedor}
                />
                <button type="submit" className="bg-red-500" name="vendedores">Buscar</button>
            </form>
        </div>
    )
}