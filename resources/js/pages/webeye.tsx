import { Head } from '@inertiajs/react';
import { useEchoPublic } from '@laravel/echo-react';
import type { GeoJSONSource } from '@maptiler/sdk';
import { Map, MapStyle } from '@maptiler/sdk';
import type { FeatureCollection, Point } from 'geojson';
import { useEffect, useRef, useState } from 'react';

import AppLayout from '@/layouts/app-layout';
import { webeye } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import type { WhazzupDto } from '@/types/ivao/WhazzupDto';
import '@maptiler/sdk/dist/maptiler-sdk.css';

const breadcrumbs: BreadcrumbItem[] = [
	{
		title: 'Webeye',
		href: webeye().url,
	},
];

const planeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00ff00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>`;
const planeIconUrl = `data:image/svg+xml;base64,${btoa(planeSvg)}`;

interface webeyeProps {
	whazzupData: WhazzupDto;
}

export default function Webeye({ whazzupData }: webeyeProps) {
	const mapRef = useRef<HTMLDivElement>(null);
	const mapInstance = useRef<Map>(null);
	const [data, setData] = useState(whazzupData);

	const mapSync = () => {
		if (mapInstance.current) {
			const pilots = data.clients.pilots || [];
			const source = mapInstance.current.getSource(
				'ivao-traffic',
			) as GeoJSONSource;

			if (source) {
				const geojsonData: FeatureCollection<Point> = {
					type: 'FeatureCollection',
					features: pilots
						.filter(
							(pilot) =>
								pilot.lastTrack?.longitude != null &&
								pilot.lastTrack?.latitude != null,
						)
						.map((pilot) => ({
							type: 'Feature',
							properties: {
								heading: pilot.lastTrack.heading,
								callsign: pilot.callsign,
							},
							geometry: {
								type: 'Point',
								coordinates: [
									pilot.lastTrack.longitude,
									pilot.lastTrack.latitude,
								],
							},
						})),
				};

				source.setData(geojsonData);
			}
		}
	};

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

		if (mapInstance.current) {
			mapInstance.current.on('load', () => {
				if (!mapInstance.current) return;
				// const response =
				// 	await mapInstance.current.loadImage('icon-plane-512.png');
				//
				// if (response) {
				// 	mapInstance.current.addImage('plane-icon', response.data);
				// }
				const img = new Image(24, 24);
				img.onload = () => {
					if (
						mapInstance.current &&
						!mapInstance.current.hasImage('plane-icon')
					) {
						mapInstance.current.addImage('plane-icon', img);
					}
				};
				img.src = planeIconUrl;

				mapInstance.current.addSource('ivao-traffic', {
					type: 'geojson',
					data: {
						type: 'FeatureCollection',
						features: [],
					},
				});

				mapInstance.current.addLayer({
					id: 'ivao-traffic-layer-img',
					type: 'symbol',
					source: 'ivao-traffic',
					layout: {
						'icon-image': 'plane-icon',
						// 'icon-size': 0.05,
						'icon-size': 0.8,
						'icon-allow-overlap': true,
						// 'icon-rotate': ['get', 'heading'],
						'icon-rotate': ['-', ['get', 'heading'], 45],
						'icon-rotation-alignment': 'map',
					},
				});

				mapSync();
			});
		}

		return () => {
			if (mapInstance.current) {
				mapInstance.current.remove();
				mapInstance.current = null;
			}
		};
	}, []);

	useEffect(() => {
		mapSync();
	}, [data]);

	useEchoPublic(
		'ivao-whazzup-updates',
		'IvaoWhazzupUpdatedEvent',
		(e: { whazzupData: WhazzupDto }) => {
			setData(e.whazzupData);
		},
	);

	return (
		<AppLayout breadcrumbs={breadcrumbs}>
			<Head title="Webeye" />
			<div ref={mapRef} className="h-full w-full rounded-b-lg" />
		</AppLayout>
	);
}
