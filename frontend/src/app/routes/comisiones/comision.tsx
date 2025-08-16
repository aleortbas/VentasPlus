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



    return (
        <div>
            <h1>comisiones</h1>
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
                    {data?.listarTodos?.map((item, index) => (
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

            {dataVendedor?.consolidadoPorVendedor?.map((item, index) => (
                <div key={index} className="mt-4">
                    <h3 className="text-lg font-semibold">{item.vendedor} - {item.mes}/{item.anio}</h3>
                    <table border={1}>
                        <thead>
                            <tr>
                                <th>Total Ventas</th>
                                <th>Comisión Base</th>
                                <th>Bono</th>
                                <th>Penalización</th>
                                <th>Comisión Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{item.total_ventas}</td>
                                <td>{item.comision_base}</td>
                                <td>{item.bono}</td>
                                <td>{item.penalizacion}</td>
                                <td>{item.comision_final}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            ))}
        </div>
    )
}