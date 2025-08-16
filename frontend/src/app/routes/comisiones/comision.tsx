import React, { useEffect, useState, FormEvent } from "react";
import { useNavigate } from 'react-router-dom';

import { submitToApi } from "../../routes/utils/apiHandle.ts";

export default function Comisiones() {

    const [vendedor, setVendedor] = React.useState("");
    const [nombre, setNombre] = React.useState("");
    const [dataVendedor, setDataVendedor] = React.useState("");
    const [data, setData] = React.useState([]);

    async function handleSubmit(vendedorId: string, nombre: string) {
        try {
            const response = await submitToApi(null, vendedorId, nombre, "vendedores");

            setDataVendedor({
                ...response,
                consolidadoPorVendedor: Array.isArray(response.consolidadoPorVendedor)
                    ? response.consolidadoPorVendedor
                    : [response.consolidadoPorVendedor] // convertimos a array si viene como objeto
            });
        } catch (error) {
            console.error("Error al enviar datos:", error);
        }
    }

    useEffect(() => {
        console.log("dataVendedor:", dataVendedor);
        async function fetchData() {
            try {
                const res = await fetch("http://localhost:8000/Public/api/vendedores.php", {
                    method: "GET",
                    mode: "cors"
                });
                const json = await res.json();
                const dataVendedores = json || [];
                setData(dataVendedores);
            } catch (error) {
                console.error("Error cargando datos:", error);
            }
        }

        fetchData();
    }, [dataVendedor]);

    const navigate = useNavigate();

    const handleNavigation = (path: string) => {
        navigate(path);
    };

    return (
        <div className="p-6 bg-gray-50 min-h-screen">
            <h1 className="text-3xl font-bold mb-2 text-gray-800">Comisiones</h1>
            <h2 className="text-xl font-semibold mb-6 text-gray-700">📋 Consolidado de comisiones</h2>

            {/* Tabla de todos los vendedores */}
            <div className="overflow-x-auto mb-8">
                <table className="min-w-full border border-gray-200 rounded-md shadow-sm">
                    <thead className="bg-gray-100">
                        <tr>
                            <th className="px-4 py-2 text-left text-gray-600">Nombre</th>
                            <th className="px-4 py-2 text-left text-gray-600">Fecha De Registro</th>
                            <th className="px-4 py-2 text-left text-gray-600">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {data?.listarTodos?.map((item, index) => (
                            <tr key={index} className="even:bg-gray-50 hover:bg-gray-100">
                                <td className="px-4 py-2">{item.nombre}</td>
                                <td className="px-4 py-2">{item.fecha_creacion}</td>
                                <td className="px-4 py-2">
                                    <button
                                        onClick={() => handleSubmit(item.vendedor_id, item.nombre)}
                                        className="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition"
                                    >
                                        Ver
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Consolidado por vendedor */}
            {dataVendedor?.consolidadoPorVendedor?.map((item, index) => (
                <div key={index} className="mb-6 p-4 bg-white rounded-md shadow-md">
                    <h3 className="text-lg font-semibold mb-3 text-gray-700">
                        {item.vendedor} - {item.mes}/{item.anio}
                    </h3>
                    <div className="overflow-x-auto">
                        <table className="min-w-full border border-gray-200 rounded-md">
                            <thead className="bg-gray-100">
                                <tr>
                                    <th className="px-4 py-2 text-left text-gray-600">Total Ventas</th>
                                    <th className="px-4 py-2 text-left text-gray-600">Comisión Base</th>
                                    <th className="px-4 py-2 text-left text-gray-600">Bono</th>
                                    <th className="px-4 py-2 text-left text-gray-600">Penalización</th>
                                    <th className="px-4 py-2 text-left text-gray-600">Comisión Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr className="even:bg-gray-50">
                                    <td className="px-4 py-2">{item.total_ventas}</td>
                                    <td className="px-4 py-2">{item.comision_base}</td>
                                    <td className="px-4 py-2">{item.bono}</td>
                                    <td className="px-4 py-2">{item.penalizacion}</td>
                                    <td className="px-4 py-2">{item.comision_final}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            ))}
            <button
                onClick={() => handleNavigation('/')}
                className="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition"
            >
                Volver
            </button>
        </div>

    )
}