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
            // Pasamos apiName porque no viene de un form
            const response = await submitToApi(null, vendedorId, nombre, "vendedores");
            setDataVendedor(response);
        } catch (error) {
            console.error("Error al enviar datos:", error);
        }
    }

    async function handleSubmitForm(e: FormEvent<HTMLFormElement>) {
        e.preventDefault(); // evita la recarga
        try {
            const response = await submitToApi(null, null, nombre, "vendedores");
            setDataVendedor(response);
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
                const dataVendedores = json?.listarTodos || [];
                setData(dataVendedores);
                console.log("Datos recibidos:", dataVendedores);
            } catch (error) {
                console.error("Error cargando datos:", error);
            }
        }

        fetchData();
    }, [dataVendedor]);



    return (
        <div>
            <h1>comisiones</h1>
            <form onSubmit={handleSubmitForm}>
                <h1 className="text-2xl font-bold mb-4">INPUT</h1>
                <input type="text" className="border border-gray-300 p-2 mb-4" placeholder="Enter your artist name"
                    onChange={(e) => setNombre(e.target.value)} value={nombre}
                />
                <button type="submit" className="bg-red-500" name="vendedores">Buscar</button>
            </form>

            <h2 className="text-xl font-bold mb-4">📋 Consolidado de comisiones</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Fecha De Registro</th>
                        <th>Comisiones</th>
                    </tr>
                </thead>
                <tbody>
                    {data.map((item, index) => (
                        <tr key={index}>
                            <td>{item.nombre}</td>
                            <td>{item.fecha_creacion}</td>
                            <td>
                                <button onClick={() => handleSubmit(item.vendedor_id, item.nombre)}>Ver</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    )
}