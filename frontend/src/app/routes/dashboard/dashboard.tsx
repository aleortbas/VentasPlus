import React, { useEffect, useState } from "react";
import { BarChart, Bar, XAxis, YAxis, Tooltip, PieChart, Pie, Cell, Legend, CartesianGrid } from "recharts";

import { useNavigate } from 'react-router-dom';


export default function Dashboard() {
    const [data, setData] = useState<any>(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const res = await fetch("http://localhost/backend/Public/api/comisiones.php", {
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
        <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        <h2 className="text-xl font-bold mb-4">📋 Consolidado de comisiones</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Año</th>
                        <th>Mes</th>
                        <th>Total Ventas</th>
                        <th>Comisión Base</th>
                        <th>Bono</th>
                        <th>Penalización</th>
                        <th>Comisión Final</th>
                    </tr>
                </thead>
                <tbody>
                    {data.listarComisionMensual.map((item, index) => (
                        <tr key={index}>
                            <td>{item.vendedor}</td>
                            <td>{item.anio}</td>
                            <td>{item.mes}</td>
                            <td>{item.total_ventas}</td>
                            <td>{item.comision_base}</td>
                            <td>{item.bono}</td>
                            <td>{item.penalizacion}</td>
                            <td>{item.comision_final}</td>
                        </tr>
                    ))}
                </tbody>
            </table>


            {/* Top 5 vendedores */}
            <div className="bg-white shadow rounded-2xl p-4">
                <h2 className="text-xl font-bold mb-4">🏆 Top 5 Vendedores por Comisión</h2>
                <BarChart width={400} height={300} data={data.listarTopCincoComision}>
                    <XAxis dataKey="vendedor" />
                    <YAxis />
                    <Tooltip />
                    <Bar dataKey="comision_final_pagar" fill="#4F46E5" />
                </BarChart>
            </div>

            {/* Total comisiones por mes */}
            <div className="bg-white shadow rounded-2xl p-4">
                <h2 className="text-xl font-bold mb-4">📅 Total Comisiones por Mes</h2>
                <BarChart width={500} height={300} data={data.listarTotalComisionMes}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis dataKey="mes" />
                    <YAxis dataKey="comision_final_pagar" />
                    <Tooltip />
                    <Bar dataKey="comision_final_pagar" fill="#4F46E5" />
                </BarChart>
            </div>

            {/* Porcentaje vendedores con bono */}
            <div className="bg-white shadow rounded-2xl p-4 col-span-1 md:col-span-2">
                <h2 className="text-xl font-bold mb-4">🎯 Porcentaje de Vendedores con Bono</h2>
                {data.listarPorcentajeVendedoresConBono.map((item) => (
                    <div>
                        <h3 className="text-lg font-semibold mb-2">{item.anio} - {item.mes}</h3>
                        <PieChart width={400} height={300} key={`${item.anio}-${item.mes}`}>
                            <Pie
                                data={[
                                    { name: "Con Bono", value: parseFloat(item.porcentaje_con_bono) },
                                    { name: "Sin Bono", value: 100 - parseFloat(item.porcentaje_con_bono) }
                                ]}
                                cx="50%"
                                cy="50%"
                                outerRadius={100}
                                fill="#8884d8"
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
            <button onClick={() => handleNavigation('/')} className="bg-red-500" name="">Volver</button>

        </div>
    );
}
