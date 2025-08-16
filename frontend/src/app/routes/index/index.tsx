import React, { useEffect, useState } from "react";
import { useNavigate } from 'react-router-dom';

import { submitToApi } from "../utils/apiHandle.ts";

export default function Index() {

    const [file, setFile] = useState<File | null>(null);


    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files.length > 0) {
            setFile(e.target.files[0]);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!file) return;

        const formData = new FormData();
        formData.append("excel", file);

        try {
            const res = await fetch("http://localhost:8000/Public/api/importar_ventas.php", {
                method: "POST",
                body: formData
            });
            const data = await res.json();
            console.log("Respuesta del servidor:", data);
        } catch (error) {
            console.error("Error al subir archivo:", error);
        }
    };

    const navigate = useNavigate();

    const handleNavigation = (path: string) => {
        navigate(path);
    };

    return (
        <>
            <form onSubmit={handleSubmit}>
                <input type="file" accept=".xlsx,.xls,.csv" onChange={handleFileChange} />
                <button type="submit">Subir Excel</button>
            </form>
            <div>
                <button onClick={() => handleNavigation('/dashboard')} className="bg-red-500" name="artist">Dashboard</button>
                <button onClick={() => handleNavigation('/comision')} className="bg-red-500" name="artist">Comisiones</button>
            </div>
        </>
    )
}