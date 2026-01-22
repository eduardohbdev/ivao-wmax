import { Head } from '@inertiajs/react';
import { Map, MapStyle } from '@maptiler/sdk';
import { useEffect, useRef } from 'react';

import AppLayout from '@/layouts/app-layout';
import { webeye } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import '@maptiler/sdk/dist/maptiler-sdk.css';

const breadcrumbs: BreadcrumbItem[] = [
	{
		title: 'Webeye',
		href: webeye().url,
	},
];

export default function Webeye() {
	const mapRef = useRef<HTMLDivElement>(null);
	const mapInstance = useRef<Map>(null);

	useEffect(() => {
		if (!mapRef.current || mapInstance.current) return;

		mapInstance.current = new Map({
			container: mapRef.current,
			style: MapStyle.STREETS_V2.DARK,
			apiKey: import.meta.env.VITE_MAPTILER_API_KEY,
			center: [-47.908615, -15.863071],
			zoom: 3,
			projection: 'globe',
		});

		return () => {
			if (mapInstance.current) {
				mapInstance.current.remove();
				mapInstance.current = null;
			}
		};
	}, []);

	return (
		<AppLayout breadcrumbs={breadcrumbs}>
			<Head title="Webeye" />
			<div ref={mapRef} className="h-full w-full rounded-b-lg" />
		</AppLayout>
	);
}
