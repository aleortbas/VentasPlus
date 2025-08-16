import React, { useEffect, useState } from "react";
import { BarChart, Bar, XAxis, YAxis, Tooltip, PieChart, Pie, Cell, Legend, CartesianGrid } from "recharts";

import { useNavigate } from 'react-router-dom';


export default function Dashboard() {
    const [data, setData] = useState<any>(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const res = await fetch("http://localhost:8000/Public/api/comisiones.php", {
                    method: "GET",
                    mode: "cors"
                });
                const json = await res.json();
                console.log("Datos recibidos:", json);
                setData(json);
            } catch (error) {
                console.error("Error cargando datos:", error);
            }
        }

        fetchData();
    }, []);

    const navigate = useNavigate();

    const handleNavigation = (path: string) => {
        navigate(path);
    };

    if (!data) return <p className="text-center mt-10">Cargando dashboard...</p>;

    return (
        <div className="p-6 bg-gray-50 min-h-screen">
            <h2 className="text-2xl font-bold mb-6 text-gray-800">📋 Consolidado de comisiones</h2>

            {/* Tabla consolidado mensual - full width */}
            <div className="overflow-x-auto bg-white shadow rounded-md p-4 mb-8">
                <table className="min-w-full border border-gray-200 rounded-md">
                    <thead className="bg-gray-100">
                        <tr>
                            {["Vendedor", "Año", "Mes", "Total Ventas", "Comisión Base", "Bono", "Penalización", "Comisión Final"].map((header) => (
                                <th key={header} className="px-4 py-2 text-left text-gray-600">{header}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {data.listarComisionMensual.map((item, index) => (
                            <tr key={index} className="even:bg-gray-50 hover:bg-gray-100">
                                <td className="px-4 py-2">{item.vendedor}</td>
                                <td className="px-4 py-2">{item.anio}</td>
                                <td className="px-4 py-2">{item.mes}</td>
                                <td className="px-4 py-2">{item.total_ventas}</td>
                                <td className="px-4 py-2">{item.comision_base}</td>
                                <td className="px-4 py-2">{item.bono}</td>
                                <td className="px-4 py-2">{item.penalizacion}</td>
                                <td className="px-4 py-2">{item.comision_final}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Grid for charts */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                {/* Top 5 vendedores */}
                <div className="bg-white shadow rounded-2xl p-4">
                    <h2 className="text-xl font-bold mb-4 text-gray-700">🏆 Top 5 Vendedores por Comisión</h2>
                    <BarChart width={400} height={300} data={data.listarTopCincoComision}>
                        <XAxis dataKey="vendedor" />
                        <YAxis />
                        <Tooltip />
                        <Bar dataKey="comision_final_pagar" fill="#4F46E5" />
                    </BarChart>
                </div>

                {/* Total comisiones por mes */}
                <div className="bg-white shadow rounded-2xl p-4">
                    <h2 className="text-xl font-bold mb-4 text-gray-700">📅 Total Comisiones por Mes</h2>
                    <BarChart width={500} height={300} data={data.listarTotalComisionMes}>
                        <CartesianGrid strokeDasharray="3 3" />
                        <XAxis dataKey="mes" />
                        <YAxis dataKey="comision_final_pagar" />
                        <Tooltip />
                        <Bar dataKey="comision_final_pagar" fill="#4F46E5" />
                    </BarChart>
                </div>

                {/* Porcentaje vendedores con bono */}
                <div className="bg-white shadow rounded-2xl p-4 md:col-span-2">
                    <h2 className="text-xl font-bold mb-4 text-gray-700">🎯 Porcentaje de Vendedores con Bono</h2>
                    {data.listarPorcentajeVendedoresConBono.map((item) => (
                        <div key={`${item.anio}-${item.mes}`} className="mb-6">
                            <h3 className="text-lg font-semibold mb-2">{item.anio} - {item.mes}</h3>
                            <PieChart width={400} height={300}>
                                <Pie
                                    data={[
                                        { name: "Con Bono", value: parseFloat(item.porcentaje_con_bono) },
                                        { name: "Sin Bono", value: 100 - parseFloat(item.porcentaje_con_bono) }
                                    ]}
                                    cx="50%"
                                    cy="50%"
                                    outerRadius={100}
                                    dataKey="value"
                                    label
                                >
                                    <Cell fill="#22C55E" />
                                    <Cell fill="#EF4444" />
                                </Pie>
                                <Legend />
                            </PieChart>
                        </div>
                    ))}
                </div>

                {/* Botón volver */}
                <div className="md:col-span-2 flex justify-start mt-4">
                    <button
                        onClick={() => handleNavigation('/')}
                        className="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition"
                    >
                        Volver
                    </button>
                </div>

            </div>
        </div>


    );
}
