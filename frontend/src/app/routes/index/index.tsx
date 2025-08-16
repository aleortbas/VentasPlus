import React, { useEffect } from "react";
import { useNavigate } from 'react-router-dom';

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

    const navigate = useNavigate();

    const handleNavigation = (path: string) => {
      navigate(path);
    };

    return (
        <div>
            <button onClick={() => handleNavigation('/Dashboard')} className="bg-red-500" name="artist">Dashboard</button>
        </div>
    )
}