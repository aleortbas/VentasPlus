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
            <div className="min-h-screen flex flex-col items-center justify-center bg-gray-50 p-6">
                <h1 className="text-3xl font-bold mb-6 text-gray-800">Reportes de Vendedores</h1>

                <form
                    onSubmit={handleSubmit}
                    className="flex flex-col sm:flex-row items-center gap-4 mb-8 w-full max-w-md"
                >
                    <input
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        onChange={handleFileChange}
                        className="border border-gray-300 rounded-md p-2 w-full sm:w-auto"
                    />
                    <button
                        type="submit"
                        className="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition"
                    >
                        Subir Excel
                    </button>
                </form>

                <div className="flex gap-4">
                    <button
                        onClick={() => handleNavigation('/dashboard')}
                        className="bg-red-500 text-white px-6 py-3 rounded-md hover:bg-red-600 transition"
                    >
                        Dashboard
                    </button>
                    <button
                        onClick={() => handleNavigation('/comision')}
                        className="bg-green-500 text-white px-6 py-3 rounded-md hover:bg-green-600 transition"
                    >
                        Comisiones
                    </button>
                </div>
            </div>
        </>

    )
}